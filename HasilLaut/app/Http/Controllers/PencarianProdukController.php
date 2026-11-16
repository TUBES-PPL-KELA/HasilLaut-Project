<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PencarianProdukController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = Str::of($request->string('q')->toString())
            ->trim()
            ->squish()
            ->toString();

        $products = Product::query()
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($keyword) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(hub_name) LIKE ?', [$keyword]);
                });
            })
            ->withSum('inventoryBatches as available_stock', 'stock')
            ->orderBy('name')
            ->get();

        return view('pembeli.pencarianIkan', compact('products', 'search'));
    }
}
