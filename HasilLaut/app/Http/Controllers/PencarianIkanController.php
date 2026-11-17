<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PencarianIkanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string'],
        ]);
        $keyword = Str::squish(trim($validated['q'] ?? ''));

        $products = Product::query()
            ->with('category')
            ->withSum('inventoryBatches as available_stock', 'stock')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . Str::lower($keyword) . '%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(hub_name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(description) LIKE ?', [$like])
                        ->orWhereHas('category', function ($categoryQuery) use ($like) {
                            $categoryQuery->whereRaw('LOWER(name) LIKE ?', [$like]);
                        });
                });
            })
            ->latest('id_product')
            ->paginate(12)
            ->withQueryString();

        return view('pembeli.pencarianIkan', compact('products', 'keyword'));
    }
}
