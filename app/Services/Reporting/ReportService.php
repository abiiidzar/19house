<?php

namespace App\Services\Reporting;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PosTransaction;
use App\Models\PosTransactionItem;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\Setting;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public const CHANNEL_ALL = 'ALL';

    public const CHANNEL_ONLINE = 'ONLINE';

    public const CHANNEL_POS = 'POS';

    private const PAID_ORDER_STATUSES = [
        Order::STATUS_PAID,
        Order::STATUS_PROCESSING,
        Order::STATUS_READY_TO_SHIP,
        Order::STATUS_SHIPPED,
        Order::STATUS_DELIVERED,
        Order::STATUS_COMPLETED,
    ];

    public function sales(CarbonInterface $start, CarbonInterface $end, string $channel = self::CHANNEL_ALL): array
    {
        $online = ['revenue' => 0, 'orders' => 0, 'transactions' => 0, 'units_sold' => 0];
        $pos = ['revenue' => 0, 'orders' => 0, 'transactions' => 0, 'units_sold' => 0];

        if ($channel !== self::CHANNEL_POS) {
            $orders = $this->onlineOrders($start, $end);
            $online['revenue'] = (int) (clone $orders)->sum('total');
            $online['orders'] = (clone $orders)->count();
            $online['transactions'] = $online['orders'];
            $online['units_sold'] = (int) OrderItem::query()
                ->whereHas('order', fn (Builder $query) => $this->filterOnlineOrders($query, $start, $end))
                ->sum('quantity');
        }

        if ($channel !== self::CHANNEL_ONLINE) {
            $transactions = $this->posTransactions($start, $end);
            $pos['revenue'] = (int) (clone $transactions)->sum('total');
            $pos['transactions'] = (clone $transactions)->count();
            $pos['units_sold'] = (int) PosTransactionItem::query()
                ->whereHas('transaction', fn (Builder $query) => $this->filterPosTransactions($query, $start, $end))
                ->sum('quantity');
        }

        $revenue = $online['revenue'] + $pos['revenue'];
        $transactions = $online['transactions'] + $pos['transactions'];

        return [
            'revenue' => $revenue,
            'orders' => $online['orders'],
            'transactions' => $transactions,
            'units_sold' => $online['units_sold'] + $pos['units_sold'],
            'aov' => $transactions > 0 ? (int) round($revenue / $transactions) : 0,
            'online' => $online,
            'pos' => $pos,
        ];
    }

    public function salesTrend(CarbonInterface $start, CarbonInterface $end, string $channel = self::CHANNEL_ALL): array
    {
        $days = [];

        if ($channel !== self::CHANNEL_POS) {
            // The first PAID event is the sale date; grouping by order.created_at misattributes delayed payments.
            $firstPaid = DB::table('payment_events')
                ->select('payment_id')
                ->selectRaw('MIN(created_at) as sale_at')
                ->where('event_type', 'PAID')
                ->groupBy('payment_id');

            $online = DB::table('orders')
                ->join('payments', 'payments.order_id', '=', 'orders.id')
                ->joinSub($firstPaid, 'paid_events', 'paid_events.payment_id', '=', 'payments.id')
                ->where('payments.status', Payment::STATUS_PAID)
                ->whereIn('orders.status', self::PAID_ORDER_STATUSES)
                ->whereBetween('paid_events.sale_at', [$start, $end])
                ->selectRaw('DATE(paid_events.sale_at) as day, SUM(orders.total) as revenue, COUNT(*) as transactions')
                ->groupByRaw('DATE(paid_events.sale_at)')
                ->get();

            foreach ($online as $row) {
                $days[$row->day] = ['date' => $row->day, 'online' => (int) $row->revenue, 'pos' => 0];
            }
        }

        if ($channel !== self::CHANNEL_ONLINE) {
            $pos = $this->posTransactions($start, $end)
                ->selectRaw('DATE(payment_confirmed_at) as day, SUM(total) as revenue, COUNT(*) as transactions')
                ->groupByRaw('DATE(payment_confirmed_at)')
                ->get();

            foreach ($pos as $row) {
                $days[$row->day] ??= ['date' => $row->day, 'online' => 0, 'pos' => 0];
                $days[$row->day]['pos'] = (int) $row->revenue;
            }
        }

        ksort($days);

        return array_values(array_map(function (array $day): array {
            $day['total'] = $day['online'] + $day['pos'];

            return $day;
        }, $days));
    }

    public function products(CarbonInterface $start, CarbonInterface $end, string $channel = self::CHANNEL_ALL, int $limit = 50): array
    {
        $products = [];
        $variants = [];

        $add = function (OrderItem|PosTransactionItem $item) use (&$products, &$variants): void {
            $snapshot = $item->sku_snapshot ?? [];
            $sku = $item->sku;
            $productName = $snapshot['product_name'] ?? 'Unknown product';
            $variantName = $snapshot['variant_name'] ?? 'Unknown variant';
            $productKey = $sku?->product_id ? 'id:'.$sku->product_id : 'name:'.$productName;
            $variantKey = $sku?->product_variant_id ? 'id:'.$sku->product_variant_id : $productKey.':'.$variantName;
            $units = (int) $item->quantity;
            $gross = (int) $item->price * $units;

            $products[$productKey] ??= ['name' => $productName, 'units_sold' => 0, 'gross_item_revenue' => 0];
            $products[$productKey]['units_sold'] += $units;
            $products[$productKey]['gross_item_revenue'] += $gross;

            $variants[$variantKey] ??= ['product_name' => $productName, 'name' => $variantName, 'units_sold' => 0, 'gross_item_revenue' => 0];
            $variants[$variantKey]['units_sold'] += $units;
            $variants[$variantKey]['gross_item_revenue'] += $gross;
        };

        if ($channel !== self::CHANNEL_POS) {
            foreach (OrderItem::query()->whereHas('order', fn (Builder $query) => $this->filterOnlineOrders($query, $start, $end))->with('sku')->lazyById(500) as $item) {
                $add($item);
            }
        }
        if ($channel !== self::CHANNEL_ONLINE) {
            foreach (PosTransactionItem::query()->whereHas('transaction', fn (Builder $query) => $this->filterPosTransactions($query, $start, $end))->with('sku')->lazyById(500) as $item) {
                $add($item);
            }
        }

        $sort = fn (array $a, array $b) => $b['units_sold'] <=> $a['units_sold'] ?: strcmp($a['name'], $b['name']);
        $productRows = array_values($products);
        $variantRows = array_values($variants);
        usort($productRows, $sort);
        usort($variantRows, $sort);

        return [
            'products' => array_slice($productRows, 0, $limit),
            'variants' => array_slice($variantRows, 0, $limit),
            'best_sellers' => array_slice($productRows, 0, 5),
            'product_count' => count($productRows),
            'variant_count' => count($variantRows),
        ];
    }

    public function inventory(CarbonInterface $start, CarbonInterface $end, ?int $lowStockThreshold = null): array
    {
        $lowStockThreshold ??= (int) Setting::valueFor('inventory_low_stock_threshold', 5);
        $summary = ['current' => 0, 'reserved' => 0, 'available' => 0, 'low_stock' => 0, 'out_of_stock' => 0];
        $alerts = ['low_stock' => [], 'out_of_stock' => []];

        foreach ($this->activeSkus()->with(['product', 'variant', 'size', 'stock'])->lazyById(500) as $sku) {
            $onHand = (int) ($sku->stock?->on_hand ?? 0);
            $reserved = (int) ($sku->stock?->reserved ?? 0);
            $available = max(0, $onHand - $reserved);
            $summary['current'] += $onHand;
            $summary['reserved'] += $reserved;
            $summary['available'] += $available;

            if ($available === 0) {
                $summary['out_of_stock']++;
                if (count($alerts['out_of_stock']) < 10) {
                    $alerts['out_of_stock'][] = $sku;
                }
            } elseif ($available <= $lowStockThreshold) {
                $summary['low_stock']++;
                if (count($alerts['low_stock']) < 10) {
                    $alerts['low_stock'][] = $sku;
                }
            }
        }

        $movements = InventoryMovement::query()->with(['sku.product', 'actor'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()->limit(20)->get();

        return compact('summary', 'alerts', 'movements');
    }

    public function inventoryRows(): LengthAwarePaginator
    {
        return $this->activeSkus()->with(['product', 'variant', 'size', 'stock'])
            ->orderBy('id')->paginate(30);
    }

    public function customers(CarbonInterface $start, CarbonInterface $end): array
    {
        $new = User::query()->whereHas('role', fn (Builder $query) => $query->where('slug', 'customer'))
            ->whereBetween('created_at', [$start, $end])->count();

        $byCustomer = [];
        foreach ($this->onlineOrders($start, $end)->with('user:id,name,email')->lazyById(500) as $order) {
            $id = $order->user_id;
            $byCustomer[$id] ??= ['name' => $order->user?->name ?? 'Unknown', 'email' => $order->user?->email ?? '', 'order_count' => 0, 'purchase_value' => 0];
            $byCustomer[$id]['order_count']++;
            $byCustomer[$id]['purchase_value'] += (int) $order->total;
        }

        $returning = 0;
        if ($byCustomer !== []) {
            $lifetimeCounts = Order::query()->whereIn('user_id', array_keys($byCustomer))
                ->whereIn('status', self::PAID_ORDER_STATUSES)
                ->whereHas('payment', fn (Builder $query) => $query->where('status', Payment::STATUS_PAID))
                ->selectRaw('user_id, COUNT(*) as order_count')
                ->groupBy('user_id')->pluck('order_count', 'user_id');
            foreach (array_keys($byCustomer) as $id) {
                if ((int) ($lifetimeCounts[$id] ?? 0) >= 2) {
                    $returning++;
                }
            }
        }

        $rows = array_values($byCustomer);
        usort($rows, fn (array $a, array $b) => $b['purchase_value'] <=> $a['purchase_value']);

        return [
            'new' => $new,
            'returning' => $returning,
            'purchasing_customers' => count($rows),
            'online_orders' => array_sum(array_column($rows, 'order_count')),
            'purchase_value' => array_sum(array_column($rows, 'purchase_value')),
            'rows' => array_slice($rows, 0, 50),
        ];
    }

    private function onlineOrders(CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $this->filterOnlineOrders(Order::query(), $start, $end);
    }

    private function filterOnlineOrders(Builder $query, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $query->whereIn('status', self::PAID_ORDER_STATUSES)
            ->whereHas('payment', fn (Builder $payment) => $payment->where('status', Payment::STATUS_PAID)
                ->whereHas('events', fn (Builder $event) => $event->where('event_type', 'PAID')
                    ->whereBetween('created_at', [$start, $end])));
    }

    private function posTransactions(CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $this->filterPosTransactions(PosTransaction::query(), $start, $end);
    }

    private function filterPosTransactions(Builder $query, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $query->where('status', PosTransaction::STATUS_COMPLETED)
            ->where('payment_status', PosTransaction::PAYMENT_CONFIRMED)
            ->whereBetween('payment_confirmed_at', [$start, $end]);
    }

    private function activeSkus(): Builder
    {
        return ProductSku::query()->where('is_active', true)
            ->whereHas('product', fn (Builder $query) => $query->where('status', Product::STATUS_ACTIVE))
            ->whereHas('variant', fn (Builder $query) => $query->where('is_active', true));
    }
}
