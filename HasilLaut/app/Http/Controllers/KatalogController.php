<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FlashSaleItem;
use App\Models\Product;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category');

        $categories = Category::all();

        // 1. Ambil daftar Flash Sale yang masih punya stok
        $flashSaleQuery = FlashSaleItem::with(['inventoryBatch.product.category', 'discount'])
            ->where('stock', '>', 0)
            ->whereHas('inventoryBatch', function ($q) {
                $q->where('stock', '>', 0);
            });

        if ($search) {
            $flashSaleQuery->whereHas('inventoryBatch.product', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($categoryId) {
            $flashSaleQuery->whereHas('inventoryBatch.product', function ($q) use ($categoryId) {
                $q->where('categories_id_categories', $categoryId);
            });
        }

        $flashSaleItems = $flashSaleQuery->get();

        // Data ringkasan untuk Banner Promo
        $maxDiscount = $flashSaleItems->max(fn($item) => $item->discount->percent ?? 0) ?? 0;
        $totalPromoStock = (float) $flashSaleItems->sum('stock');

        // 2. Ambil daftar Katalog Produk Reguler
        $productQuery = Product::with(['category', 'inventoryBatches']);

        if ($categoryId) {
            $productQuery->where('categories_id_categories', $categoryId);
        }

        if ($search) {
            $productQuery->where('name', 'like', '%' . $search . '%');
        }

        $products = $productQuery->latest()->get()->map(function ($product) {
            $product->available_stock = (float) $product->inventoryBatches->sum('stock');
            return $product;
        });

        return view('pembeli.katalog', compact(
            'categories',
            'flashSaleItems',
            'maxDiscount',
            'totalPromoStock',
            'products',
            'search',
            'categoryId'
        ));
    }
}