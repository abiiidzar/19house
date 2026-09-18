<?php

namespace App\Observers;

use App\Models\CancellationRequest;
use App\Models\HomepageSection;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use App\Services\System\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    private const FIELDS = [
        Product::class => ['name', 'category_id', 'base_price', 'status', 'is_featured'],
        ProductVariant::class => ['name', 'hex_code', 'display_order', 'is_active'],
        ProductVariantImage::class => ['path', 'is_primary', 'display_order'],
        ProductSku::class => ['sku', 'size_id', 'price', 'is_active'],
        Voucher::class => ['code', 'type', 'value', 'min_purchase', 'max_discount', 'start_date', 'end_date', 'usage_limit', 'per_user_limit', 'is_active'],
        User::class => ['role_id', 'status'],
        Order::class => ['status'],
        CancellationRequest::class => ['status', 'reviewed_by'],
        Setting::class => ['value'],
        HomepageSection::class => ['eyebrow', 'title', 'subtitle', 'button_label', 'button_path', 'image_path', 'mobile_image_path', 'text_position', 'text_color', 'sort_order', 'is_active'],
    ];

    public function __construct(private ActivityLogService $logs) {}

    public function created(Model $model): void
    {
        if (in_array($model::class, [User::class, Order::class], true)) {
            return;
        }

        $values = $this->allowed($model, $model->getAttributes());
        $this->logs->log($this->prefix($model).'.created', $model, [], $values);
    }

    public function updated(Model $model): void
    {
        $changes = $this->allowed($model, $model->getChanges());
        if ($changes === []) {
            return;
        }

        $old = [];
        $new = [];
        foreach (array_keys($changes) as $key) {
            $old[$key] = $model->getRawOriginal($key);
            $new[$key] = $model->getAttribute($key);
        }

        $action = match (true) {
            $model instanceof Product && array_key_exists('base_price', $changes),
            $model instanceof ProductSku && array_key_exists('price', $changes) => 'catalog.price_changed',
            $model instanceof User && array_key_exists('role_id', $changes) => 'user.role_changed',
            $model instanceof Order && array_key_exists('status', $changes) => 'order.status_changed',
            default => $this->prefix($model).'.updated',
        };

        $this->logs->log($action, $model, $old, $new);
    }

    public function deleted(Model $model): void
    {
        $this->logs->log($this->prefix($model).'.deleted', $model, $this->allowed($model, $model->getAttributes()));
    }

    private function allowed(Model $model, array $values): array
    {
        return array_intersect_key($values, array_flip(self::FIELDS[$model::class] ?? []));
    }

    private function prefix(Model $model): string
    {
        return match ($model::class) {
            Product::class => 'catalog.product',
            ProductVariant::class => 'catalog.variant',
            ProductVariantImage::class => 'catalog.image',
            ProductSku::class => 'catalog.sku',
            Voucher::class => 'promotion.voucher',
            User::class => 'user',
            Order::class => 'order',
            CancellationRequest::class => 'cancellation',
            Setting::class => 'system.setting',
            HomepageSection::class => 'homepage.section',
        };
    }
}
