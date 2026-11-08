<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    // Menampilkan Halaman Dasbor Flash Sale
     public function index()
    {
        // Ambil diskon global (karena disamaratakan, kita ambil data diskon pertama)
        $discount = Discount::first();

        // Ambil daftar flash sale yang sedang aktif beserta relasi ke produk
        $activeFlashSales = FlashSaleItem::with(['inventoryBatch.product', 'discount'])
            ->whereHas('inventoryBatch', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->paginate(5);

        // (Opsional untuk UI UI Kotak Kuning) Ambil batch yang H-1 Kadaluarsa
        $today = Carbon::today();
        $warningBatches = InventoryBatch::with('product')
            ->doesntHave('flashSaleItem') // Belum masuk flash sale
            ->where('stock', '>', 0)
            ->get()
            ->filter(function ($batch) use ($today) {
                if (!$batch->product || !$batch->product->max_storage_days) return false;
                $receivedDate = Carbon::parse($batch->received_date);
                $expiryDate = $receivedDate->copy()->addDays($batch->product->max_storage_days);
                // Kembalikan true jika sisa waktu tinggal 1 hari lagi
                return $today->diffInDays($expiryDate, false) == 1;
            });

        return view('admin.flash_sale.index', compact('discount', 'activeFlashSales', 'warningBatches'));
    }

    // Menyimpan atau Mengupdate Aturan Diskon Global
    public function updateGlobalDiscount(Request $request)
    {
        $request->validate([
            'percent' => 'required|numeric|min:1|max:99'
        ]);

        $discount = Discount::first();
        if ($discount) {
            $discount->update(['percent' => $request->percent]);
        } else {
            Discount::create(['percent' => $request->percent]);
        }

        return back()->with('success', 'Aturan diskon global berhasil diperbarui!');
    }

    // Menghentikan Flash Sale Manual (Tombol Merah di Figma)
    public function destroy($id)
    {
        $flashSale = FlashSaleItem::findOrFail($id);
        $flashSale->delete(); // Ini hanya menghapus dari etalase flash sale, stok aslinya tetap ada di inventory_batches

        return back()->with('success', 'Flash sale produk berhasil dihentikan.');
    }
}