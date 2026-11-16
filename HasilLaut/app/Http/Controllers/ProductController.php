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
}