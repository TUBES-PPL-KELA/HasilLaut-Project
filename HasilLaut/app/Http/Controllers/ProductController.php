<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\InventoryBatch;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
    public function index()
    {
        
        $products = Product::with(['category', 'inventoryBatches'])->latest('id_product')->get();
        
       
        $categories = Category::withCount('products')->get();

        return view('admin.product.index', compact('products', 'categories'));
    }

    
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

   
    public function store(StoreProductRequest $request)
    {
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

       
        DB::beginTransaction();

        try {
           
            $product = Product::create([
                'name'                     => $request->name,
                'categories_id_categories' => $request->categories_id_categories,
                'max_storage_days'         => $request->max_storage_days,
                'description'              => $request->description,
                'price'                    => $request->price,
                'image'                    => $imagePath,
            ]);

            
            InventoryBatch::create([
                'product_id_product' => $product->id_product,
                'users_id_users'     => 1, 
                'stock'              => $request->stock,
                'purchase_price'     => $request->purchase_price,
                'received_date'      => $request->received_date,
            ]);

            DB::commit();

            
            return redirect()->route('admin.product.index')
                             ->with('success', 'Produk dan Stok Awal berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function katalog(Request $request)
    {
        $query = Product::with('category')
            ->withSum('inventoryBatches as total_stock', 'stock'); // total stok semua batch

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('categories_id_categories', $request->kategori);
        }

        if ($request->boolean('tersedia')) {
            $query->whereHas('inventoryBatches', fn ($b) => $b->where('stock', '>', 0));
        }

        match ($request->sort) {
            'harga_asc'  => $query->orderBy('price'),
            'harga_desc' => $query->orderByDesc('price'),
            'nama'       => $query->orderBy('name'),
            default      => $query->orderByDesc('id_product'),
        };

        $products   = $query->paginate(8)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('pembeli.katalog', compact('products', 'categories'));
    }
}