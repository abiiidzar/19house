<?php

namespace Tests\Feature\Management;

use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\PosTransaction;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Services\Reporting\ReportService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingTest extends TestCase
{
    use RefreshDatabase;

    private User $management;

    private User $customer;

    private ProductSku $sku;

    protected function setUp(): void
    {
        parent::setUp();

        $this->management = User::factory()->create(['role_id' => Role::factory()->create(['slug' => 'management'])->id, 'status' => 'ACTIVE']);
        $this->customer = User::factory()->create(['role_id' => Role::factory()->create(['slug' => 'customer'])->id, 'status' => 'ACTIVE']);
        $product = Product::factory()->create(['name' => 'Report Tee', 'status' => Product::STATUS_ACTIVE]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'name' => 'Black', 'is_active' => true]);
        $this->sku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'size_id' => Size::factory()->create()->id,
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $this->sku->id, 'on_hand' => 10, 'reserved' => 2]);
    }

    private function onlineSale(string $status, string $paymentStatus, CarbonImmutable $paidAt, int $total = 200000, int $quantity = 2): Order
    {
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'status' => $status,
            'subtotal' => 200000,
            'shipping_cost' => 20000,
            'discount' => 20000,
            'total' => $total,
            'created_at' => $paidAt->subMonth(),
        ]);
        $order->items()->create([
            'product_sku_id' => $this->sku->id,
            'price' => 100000,
            'quantity' => $quantity,
            'sku_snapshot' => ['product_name' => 'Report Tee', 'variant_name' => 'Black', 'size_name' => 'M'],
        ]);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => $paymentStatus, 'amount' => $total]);
        $event = PaymentEvent::create(['payment_id' => $payment->id, 'event_type' => 'PAID', 'payload' => []]);
        $event->created_at = $paidAt;
        $event->save();

        return $order;
    }

    private function posSale(CarbonImmutable $paidAt, string $status = PosTransaction::STATUS_COMPLETED): PosTransaction
    {
        $cashierRole = Role::firstOrCreate(['slug' => 'cashier'], ['name' => 'Cashier']);
        $cashier = User::factory()->create(['role_id' => $cashierRole->id, 'status' => 'ACTIVE']);
        $sale = PosTransaction::create([
            'receipt_number' => 'POS-'.fake()->unique()->numerify('######'),
            'cashier_id' => $cashier->id,
            'subtotal' => 100000,
            'discount' => 10000,
            'total' => 90000,
            'payment_method' => 'CASH',
            'payment_status' => PosTransaction::PAYMENT_CONFIRMED,
            'payment_confirmed_at' => $paidAt,
            'amount_received' => 100000,
            'change' => 10000,
            'status' => $status,
        ]);
        $sale->items()->create([
            'product_sku_id' => $this->sku->id,
            'price' => 100000,
            'quantity' => 1,
            'sku_snapshot' => ['product_name' => 'Report Tee', 'variant_name' => 'Black', 'size_name' => 'M'],
        ]);

        return $sale;
    }

    public function test_sales_channels_use_payment_dates_and_exclude_unpaid_and_cancelled_orders(): void
    {
        $day = CarbonImmutable::parse('2026-09-14 12:00:00');
        $this->onlineSale(Order::STATUS_PAID, Payment::STATUS_PAID, $day);
        $this->onlineSale(Order::STATUS_PENDING_PAYMENT, Payment::STATUS_PENDING, $day, 999999, 10);
        $this->onlineSale(Order::STATUS_CANCELLED, Payment::STATUS_PAID, $day, 700000, 7);
        $this->posSale($day);
        $this->posSale($day->subDay());

        $start = $day->startOfDay();
        $end = $day->endOfDay();
        $reports = app(ReportService::class);
        $all = $reports->sales($start, $end);
        $this->assertSame(290000, $all['revenue']);
        $this->assertSame(1, $all['orders']);
        $this->assertSame(2, $all['transactions']);
        $this->assertSame(3, $all['units_sold']);
        $this->assertSame(145000, $all['aov']);
        $this->assertSame(200000, $reports->sales($start, $end, 'ONLINE')['revenue']);
        $this->assertSame(90000, $reports->sales($start, $end, 'POS')['revenue']);

        $trend = $reports->salesTrend($start, $end);
        $this->assertSame([['date' => '2026-09-14', 'online' => 200000, 'pos' => 90000, 'total' => 290000]], $trend);
        $products = $reports->products($start, $end);
        $this->assertSame(1, $products['product_count']);
        $this->assertSame(3, $products['best_sellers'][0]['units_sold']);
        $this->assertSame(300000, $products['best_sellers'][0]['gross_item_revenue']);
        $this->assertSame(3, $products['variants'][0]['units_sold']);
    }

    public function test_inventory_and_customer_reports_are_consistent_and_dashboard_is_read_only(): void
    {
        $day = CarbonImmutable::now();
        $this->onlineSale(Order::STATUS_PAID, Payment::STATUS_PAID, $day);
        $this->onlineSale(Order::STATUS_PAID, Payment::STATUS_PAID, $day->subMonth());
        $lowSku = ProductSku::factory()->create([
            'product_id' => $this->sku->product_id,
            'product_variant_id' => $this->sku->product_variant_id,
            'size_id' => Size::factory()->create()->id,
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $lowSku->id, 'on_hand' => 3, 'reserved' => 0]);
        ProductSku::factory()->create([
            'product_id' => $this->sku->product_id,
            'product_variant_id' => $this->sku->product_variant_id,
            'size_id' => Size::factory()->create()->id,
            'is_active' => true,
        ]);
        InventoryMovement::create([
            'product_sku_id' => $this->sku->id,
            'type' => 'STOCK_IN',
            'quantity' => 10,
            'before' => 0,
            'after' => 10,
            'reference' => 'REPORT-TEST',
        ]);

        $reports = app(ReportService::class);
        $inventory = $reports->inventory($day->startOfDay(), $day->endOfDay());
        $this->assertSame(13, $inventory['summary']['current']);
        $this->assertSame(2, $inventory['summary']['reserved']);
        $this->assertSame(11, $inventory['summary']['available']);
        $this->assertSame(1, $inventory['summary']['low_stock']);
        $this->assertSame(1, $inventory['summary']['out_of_stock']);
        $this->assertCount(1, $inventory['movements']);

        $customers = $reports->customers($day->startOfDay(), $day->endOfDay());
        $this->assertSame(1, $customers['returning']);
        $this->assertSame(1, $customers['online_orders']);
        $this->assertSame(200000, $customers['purchase_value']);

        $this->actingAs($this->management)
            ->get(route('management.dashboard', ['from' => $day->toDateString(), 'to' => $day->toDateString()]))
            ->assertOk()->assertSee('Business Overview')->assertSee('Report Tee');
        $this->get(route('management.reports.sales'))->assertOk();
        $this->get(route('management.reports.products'))->assertOk();
        $this->get(route('management.reports.inventory'))->assertOk();
        $this->get(route('management.reports.customers'))->assertOk();
        $this->post(route('admin.vouchers.store'), [])->assertForbidden();
        $this->post('/management/reports/sales')->assertStatus(405);
    }

    public function test_report_filters_reject_invalid_or_excessive_ranges(): void
    {
        $this->actingAs($this->management)
            ->get(route('management.dashboard', ['from' => '2026-09-15', 'to' => '2026-09-14']))
            ->assertSessionHasErrors('to');
        $this->get(route('management.reports.sales', ['from' => '2024-01-01', 'to' => '2026-09-14']))
            ->assertSessionHasErrors('to');
        $this->get(route('management.reports.sales', ['channel' => 'OTHER']))
            ->assertSessionHasErrors('channel');
    }
}
