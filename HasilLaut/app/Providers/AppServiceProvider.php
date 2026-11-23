<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $count = 0;
            $userId = auth()->id() ?? 1;

            if (Schema::hasTable('carts') && Schema::hasTable('cart_items')) {
                $count = (int) DB::table('cart_items')
                    ->join('carts', 'cart_items.carts_id_carts', '=', 'carts.id_carts')
                    ->where('carts.users_id_users', $userId)
                    ->sum('cart_items.quantity');
            }

            $view->with('cartBadgeCount', $count);
        });
    }
}