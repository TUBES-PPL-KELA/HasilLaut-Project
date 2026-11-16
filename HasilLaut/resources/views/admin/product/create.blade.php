<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hasil Laut Baru - Admin HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: { 50: '#f0fdfa', 700: '#0f766e', 800: '#115e59', 900: '#134e4a' },
                        btnprimary: '#164e63',
                        btnhover: '#083344',
                        darkgreen: '#022c22',
                        batchbtn: '#e6f3f4'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="w-[260px] bg-white border-r border-gray-100 flex flex-col h-full shrink-0 shadow-[2px_0_10px_rgba(0,0,0,0.02)] z-20">
        <div class="h-24 flex items-center px-8 mt-2 border-b border-gray-50">
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut Logo" class="h-12 w-auto object-contain">
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-5 space-y-6">
            <div>
                <p class="px-3 text-[11px] font-bold text-teal-700 uppercase tracking-wider mb-2 opacity-80">Beranda</p>
                <a href="#" class="flex items-center px-3 py-2.5 text-[#0f766e] font-semibold rounded-lg group transition hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <span class="text-[13px]">Analitik & Grafik</span>
                </a>
            </div>
            <div>
                <p class="px-3 text-[11px] font-bold text-teal-700 uppercase tracking-wider mb-2 opacity-80">Gudang & Katalog</p>
                <a href="{{ route('admin.product.index') }}" class="flex items-center px-3 py-2.5 bg-[#f1f5f9] text-[#64748b] rounded-lg transition mb-1">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-[13px] font-semibold">Stok Gudang</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 text-[#64748b] hover:bg-gray-50 hover:text-teal-700 rounded-lg group transition">
                    <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="text-[13px] font-semibold">Publikasi Katalog B2C</span>
                </a>
            </div>
            <div>
                <p class="px-3 text-[11px] font-bold text-teal-700 uppercase tracking-wider mb-2 opacity-80">Transaksi & Armada</p>
                <a href="#" class="flex items-center px-3 py-2.5 text-[#64748b] hover:bg-gray-50 hover:text-teal-700 rounded-lg group transition">
                    <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-[13px] font-semibold">Pesanan Masuk</span>
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex items-center bg-[#f8fafc] p-3 rounded-xl border border-gray-100 shadow-sm cursor-pointer">
                <img src="https://ui-avatars.com/api/?name=Andi+M&background=312e81&color=fff&rounded=true&bold=true" alt="Admin Avatar" class="h-9 w-9 rounded-full shadow-sm">
                <div class="ml-3">
                    <p class="text-[13px] font-bold text-[#0f172a] leading-tight">Andi M. (Admin)</p>
                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">Hub Pangandaran</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 z-10">
        <div class="flex-1 overflow-y-auto p-10 lg:p-14">
            
            <!-- Breadcrumb -->
            <div class="max-w-5xl mx-auto mb-10 border-b border-gray-200 pb-6">
                <nav class="flex text-[13px] font-bold text-teal-700 mb-3" aria-label="Breadcrumb">
                    <a href="{{ route('admin.product.index') }}" class="hover:text-teal-900 transition">Katalog Produk</a>
                    <span class="mx-2 text-gray-300">›</span>
                    <span class="text-gray-400">Tambah Hasil Laut Baru</span>
                </nav>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Tambah Hasil Laut Baru</h2>
                <p class="text-[14px] text-gray-500 font-medium">Formulir penambahan master produk baru yang terhubung ke basis data ERD product, categories, dan inventory_logs.</p>
            </div>

            @if($errors->any())
                <div class="max-w-5xl mx-auto mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm font-bold shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="max-w-5xl mx-auto bg-white rounded-[20px] shadow-sm border border-gray-200 p-10">
                <!-- Tambahkan ID ke form agar bisa dideteksi oleh JavaScript -->
                <form id="product-form" action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-14 gap-y-8">
                        
                        <!-- Kiri: Foto -->
                        <div class="lg:col-span-5 space-y-8">
                            <div>
                                <label class="block text-sm font-extrabold text-[#0f172a] mb-3">Unggah Foto Produk</label>
                                <div class="mt-1 relative flex flex-col justify-center items-center w-full h-[320px] border-2 border-[#cbd5e1] border-dashed rounded-[16px] bg-[#f8fafc] hover:bg-gray-100 transition overflow-hidden group">
                                    <div id="upload-hint" class="space-y-2 text-center flex flex-col items-center pointer-events-none z-0">
                                        <span class="font-extrabold text-[#0f766e]">Klik untuk memilih file foto</span>
                                        <p class="text-xs text-gray-400 font-bold mt-2">Format: JPG, PNG (Maks. 2MB)</p>
                                    </div>
                                    <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover z-10" />
                                    <input id="image" name="image" type="file" accept="image/jpeg, image/png, image/jpg" required onchange="previewImage(event)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                </div>
                            </div>
                        </div>

                        <!-- Kanan: Input -->
                        <div class="lg:col-span-7 space-y-6">
                            
                            <div>
                                <label for="name" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Nama Produk</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Ikan Kerapu Segar Pesisir" required 
                                    class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition">
                            </div>

                            <div>
                                <label for="categories_id_categories" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Kategori</label>
                                <select name="categories_id_categories" id="categories_id_categories" required 
                                    class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-600 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em]" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%2F%3E%3C%2Fsvg%3E');">
                                    <option value="">Pilih Kategori Produk</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_categories }}" {{ old('categories_id_categories') == $category->id_categories ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="purchase_price_view" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Harga Beli per Kg</label>
                                    <input type="text" id="purchase_price_view" value="{{ old('purchase_price') ? number_format(old('purchase_price'), 0, ',', '.') : '' }}" placeholder="Contoh: 80000" required 
                                        class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition"
                                        oninput="formatRupiah(this, 'purchase_price')">
                                    <input type="hidden" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" required>
                                </div>
                                <div>
                                    <label for="price_view" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Harga Jual per Kg (Etalase)</label>
                                    <input type="text" id="price_view" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" placeholder="Contoh: 95000" required 
                                        class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition"
                                        oninput="formatRupiah(this, 'price')">
                                    <input type="hidden" name="price" id="price" value="{{ old('price') }}" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="stock" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Stok gudang (Kg)</label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}" required min="1"
                                        class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition">
                                </div>
                                <div>
                                    <label for="received_date" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Tanggal masuk</label>
                                    <input type="date" name="received_date" id="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required
                                        class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-600 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="max_storage_days" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Ambang batas segar (Hari)</label>
                                    <input type="number" name="max_storage_days" id="max_storage_days" value="{{ old('max_storage_days', 7) }}" required min="1"
                                        class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-sm rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none font-medium transition">
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-[13px] font-extrabold text-[#0f172a] mb-2">Deskripsi Produk</label>
                                <textarea name="description" id="description" rows="4" placeholder="Tuliskan deskripsi singkat kesegaran, asal nelayan, dan kualitas rantai dingin..." required
                                    class="w-full px-5 py-3.5 bg-[#f8fafc] border border-gray-200 text-gray-900 text-[13px] rounded-[10px] focus:bg-white focus:ring-1 focus:ring-[#0f766e] focus:border-[#0f766e] outline-none resize-none font-medium transition">{{ old('description') }}</textarea>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-100 mt-6">
                                <a href="{{ route('admin.product.index') }}" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-600 font-extrabold rounded-lg hover:bg-gray-50 transition text-[13px]">
                                    Batal
                                </a>
                                <!-- Tambahkan ID submit-btn ke tombol simpan -->
                                <button type="submit" id="submit-btn" class="px-8 py-3.5 bg-[#164e63] hover:bg-[#083344] text-white font-extrabold rounded-lg shadow-md transition text-[13px] flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span id="btn-text">Simpan Produk Baru</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="h-24"></div> 
        </div>
    </main>

    <!-- JS Scripts -->
    <script>
        // Fitur Preview Gambar
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const hint = document.getElementById('upload-hint');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    hint.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Fitur Format Rupiah otomatis
        function formatRupiah(element, hiddenInputId) {
            let rawValue = element.value.replace(/[^0-9]/g, '');
            document.getElementById(hiddenInputId).value = rawValue;
            element.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Mencegah Spam Klik / Double Submit
        document.getElementById('product-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            
            // Nonaktifkan tombol agar tidak bisa diklik lagi
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-wait');
            
            // Ubah teks tombol memberikan indikator proses berjalan
            if(btnText) {
                btnText.innerText = 'Menyimpan...';
            }
        });
    </script>
</body>
</html>