<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        $topTrendProducts = Product::query()
            ->where('is_top_tren', true)
            ->whereHas('inventoryBatches', fn ($query) => $query->where('stock', '>', 0))
            ->withSum(
                ['inventoryBatches as available_stock' => fn ($query) => $query->where('stock', '>', 0)],
                'stock'
            )
            ->latest('id_product')
            ->limit(6)
            ->get();

        return view('welcome', compact('topTrendProducts'));
    }
}
