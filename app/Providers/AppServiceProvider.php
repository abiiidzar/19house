<?php

namespace App\Providers;

use App\Models\CancellationRequest;
use App\Models\CustomerAddress;
use App\Models\HomepageSection;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use App\Observers\AuditObserver;
use App\Policies\CustomerAddressPolicy;
use App\Policies\OrderPolicy;
use App\Services\Catalog\PriceResolver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PriceResolver::class, function ($app) {
            return new PriceResolver;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.admin', 'layouts.account'], function ($view): void {
            $view->with('unreadNotificationCount', auth()->user()?->unreadNotifications()->count() ?? 0);
        });

        foreach ([Product::class, ProductVariant::class, ProductVariantImage::class, ProductSku::class, Voucher::class, User::class, Order::class, CancellationRequest::class, Setting::class, HomepageSection::class] as $model) {
            $model::observe(AuditObserver::class);
        }

        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(CustomerAddress::class, CustomerAddressPolicy::class);

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->numbers();
        });
    }
}
