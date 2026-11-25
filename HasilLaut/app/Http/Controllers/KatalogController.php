<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FlashSaleItem;
use App\Models\Product;


class KatalogController extends Controller
{
public function index(Request $request, PencarianIkanController $pencarianController)
    {
        $categoryId = $request->query('category');

        $categories = Category::all();

        // 1. Ambil daftar Flash Sale yang masih punya stok
        $flashSaleQuery = FlashSaleItem::with(['inventoryBatch.product.category', 'discount'])
            ->where('stock', '>', 0)
            ->whereHas('inventoryBatch', function ($q) {
                $q->where('stock', '>', 0);
            });

        if ($request->filled('search') || $request->filled('q')) {
            $flashSaleQuery->whereHas('inventoryBatch.product', function ($q) use ($pencarianController, $request) {
                $pencarianController->index($request, $q);
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

        // Suntikkan logika filter pencarian dari PencarianIkanController
        $search = $pencarianController->index($request, $productQuery);

        $activeFlashSaleStockByBatch = FlashSaleItem::where('stock', '>', 0)
            ->pluck('stock', 'inventory_batches_id_inventory_batches');

        $products = $productQuery->latest()->get()->map(function ($product) use ($activeFlashSaleStockByBatch) {
            $totalBatchStock = (float) $product->inventoryBatches->sum('stock');
            $promoStock = (float) $product->inventoryBatches->sum(function ($batch) use ($activeFlashSaleStockByBatch) {
                return (float) ($activeFlashSaleStockByBatch[$batch->id_inventory_batches] ?? 0);
            });

            $product->available_stock = max(0, $totalBatchStock - $promoStock);
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

    public function show(Request $request, $id)
    {
        // Ambil detail produk beserta kategori dan batch stok yang masih tersedia (urut FIFO)
        $product = Product::with([
            'category',
            'inventoryBatches' => function ($query) {
                $query->where('stock', '>', 0)->orderBy('received_date', 'asc');
            }
        ])->findOrFail($id);

        // Cek apakah halaman dibuka dari kartu Flash Sale (?flash_sale_item_id=...)
        $flashSaleItem = null;
        if ($request->filled('flash_sale_item_id')) {
            $flashSaleItem = FlashSaleItem::with(['inventoryBatch', 'discount'])
                ->where('id_flash_sale_item', $request->query('flash_sale_item_id'))
                ->where('stock', '>', 0)
                ->whereHas('inventoryBatch', function ($q) use ($product) {
                    $q->where('product_id_product', $product->id_product)
                      ->where('stock', '>', 0);
                })
                ->first();
        }

        // Hitung stok tersedia dan tanggal masuk gudang (FIFO / Batch Flash Sale)
        if ($flashSaleItem) {
            $availableStock = (float) $flashSaleItem->stock;
            $receivedDate = $flashSaleItem->inventoryBatch->received_date ?? null;
        } else {
            $activeFlashSaleStockByBatch = FlashSaleItem::whereIn(
                'inventory_batches_id_inventory_batches',
                $product->inventoryBatches->pluck('id_inventory_batches')
            )->where('stock', '>', 0)->pluck('stock', 'inventory_batches_id_inventory_batches');

            $availableStock = (float) $product->inventoryBatches->sum(function ($batch) use ($activeFlashSaleStockByBatch) {
                $promoQty = (float) ($activeFlashSaleStockByBatch[$batch->id_inventory_batches] ?? 0);
                return max(0, (float) $batch->stock - $promoQty);
            });

            $regularBatch = $product->inventoryBatches->first(function ($batch) use ($activeFlashSaleStockByBatch) {
                $promoQty = (float) ($activeFlashSaleStockByBatch[$batch->id_inventory_batches] ?? 0);
                return ((float) $batch->stock - $promoQty) > 0;
            });
            $receivedDate = $regularBatch ? $regularBatch->received_date : null;
        }

        // Ambil 4 produk rekomendasi lainnya beserta stoknya
        $relatedProducts = Product::with('inventoryBatches')
            ->where('id_product', '!=', $product->id_product)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($rel) {
                $rel->available_stock = (float) $rel->inventoryBatches->sum('stock');
                return $rel;
            });

        return view('pembeli.detail-produk', compact(
            'product',
            'flashSaleItem',
            'availableStock',
            'receivedDate',
            'relatedProducts'
        ));
    }
}
