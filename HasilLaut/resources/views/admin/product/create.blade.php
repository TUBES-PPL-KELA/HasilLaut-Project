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
                        teal: { 700: '#0f766e', 800: '#115e59', 900: '#134e4a' },
                        btnprimary: '#164e63',
                        btnhover: '#083344'
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
<body class="bg-slate-50 flex h-screen overflow-hidden">

    <!-- SIDEBAR KIRI -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col h-full shrink-0 shadow-sm z-20">
        <div class="h-24 flex items-center px-8 border-b border-gray-50 mt-2">
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut Logo" class="h-14 w-auto object-contain">
        </div>

        <div class="flex-1 overflow-y-auto py-8 px-5 space-y-8">
            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Beranda</p>
                <a href="#" class="flex items-center px-3 py-3 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition duration-200">
                    <svg class="w-5 h-5 mr-3.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <span class="text-sm font-semibold">Analitik & Grafik</span>
                </a>
            </div>
            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Gudang & Katalog</p>
                <a href="{{ route('admin.product.index') }}" class="flex items-center px-3 py-3 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition duration-200 mb-1.5">
                    <svg class="w-5 h-5 mr-3.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm font-semibold">Stok Gudang</span>
                </a>
            </div>
            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Transaksi & Armada</p>
                <a href="#" class="flex items-center px-3 py-3 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition duration-200">
                    <svg class="w-5 h-5 mr-3.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-sm font-semibold">Pesanan Masuk</span>
                </a>
            </div>
        </div>

        <div class="p-6 border-t border-gray-50 bg-white">
            <div class="flex items-center bg-gray-50 p-3 rounded-xl border border-gray-100 cursor-pointer">
                <img src="https://ui-avatars.com/api/?name=Andi+M&background=312e81&color=fff&rounded=true&bold=true" alt="Admin Avatar" class="h-10 w-10 rounded-full shadow-sm">
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-800 leading-tight">Andi M. (Admin)</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Hub Pangandaran</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 z-10">
        
        <div class="flex-1 overflow-y-auto p-10 lg:p-14">
            
            <div class="max-w-5xl mx-auto mb-10">
                <nav class="flex text-sm font-bold text-teal-700 mb-3" aria-label="Breadcrumb">
                    <a href="{{ route('admin.product.index') }}" class="hover:text-teal-900 transition">Katalog Produk</a>
                    <span class="mx-3 text-gray-300">›</span>
                    <span class="text-gray-400">Tambah Hasil Laut Baru</span>
                </nav>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Tambah Hasil Laut Baru</h2>
                <p class="text-gray-500 font-medium">Formulir penambahan master produk baru yang terhubung ke basis data ERD product dan inventory_logs.</p>
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

            <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-200 p-10">
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-14 gap-y-8">
                        
                        <!-- Kiri: Foto -->
                        <div class="lg:col-span-5 space-y-8">
                            <div>
                                <label class="block text-sm font-extrabold text-gray-900 mb-3">Unggah Foto Produk</label>
                                <div class="mt-1 relative flex flex-col justify-center items-center w-full h-[300px] border-2 border-gray-300 border-dashed rounded-2xl bg-white hover:bg-gray-50 transition overflow-hidden group">
                                    <div id="upload-hint" class="space-y-2 text-center flex flex-col items-center pointer-events-none z-0">
                                        <span class="font-extrabold text-teal-700">Klik untuk memilih file foto</span>
                                        <p class="text-xs text-gray-400 font-bold mt-2">Format: JPG, PNG (Maks. 2MB)</p>
                                    </div>
                                    <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover z-10" />
                                    <input id="image" name="image" type="file" accept="image/jpeg, image/png, image/jpg" required onchange="previewImage(event)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                </div>
                            </div>
                        </div>

                        <!-- Kanan: Form -->
                        <div class="lg:col-span-7 space-y-7">
                            <div>
                                <label for="name" class="block text-sm font-extrabold text-gray-900 mb-2">Nama Produk</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Ikan Kerapu Segar Pesisir" required 
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                            </div>

                            <!-- INPUT KATEGORI DIKEMBALIKAN -->
                            <div>
                                <label for="categories_id_categories" class="block text-sm font-extrabold text-gray-900 mb-2">Kategori</label>
                                <select name="categories_id_categories" id="categories_id_categories" required 
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                    <option value="">Pilih Kategori Produk</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_categories }}" {{ old('categories_id_categories') == $category->id_categories ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                                <div>
                                    <label for="purchase_price" class="block text-sm font-extrabold text-gray-900 mb-2">Harga Beli per Kg (Modal)</label>
                                    <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" placeholder="Contoh: 80000" required min="0" step="100"
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                </div>
                                <div>
                                    <label for="price" class="block text-sm font-extrabold text-gray-900 mb-2">Harga Jual per Kg (Etalase)</label>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="Contoh: 95000" required min="0" step="100"
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                                <div>
                                    <label for="stock" class="block text-sm font-extrabold text-gray-900 mb-2">Stok gudang awal (Kg)</label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}" required min="1"
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                </div>
                                <div>
                                    <label for="received_date" class="block text-sm font-extrabold text-gray-900 mb-2">Tanggal masuk</label>
                                    <input type="date" name="received_date" id="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                                <div>
                                    <label for="max_storage_days" class="block text-sm font-extrabold text-gray-900 mb-2">Ambang batas segar (Hari)</label>
                                    <input type="number" name="max_storage_days" id="max_storage_days" value="{{ old('max_storage_days', 7) }}" required min="1"
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none font-medium transition">
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-extrabold text-gray-900 mb-2">Deskripsi Produk</label>
                                <textarea name="description" id="description" rows="5" placeholder="Tuliskan deskripsi singkat kesegaran, asal nelayan, dan kualitas rantai dingin..." required
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-700 focus:border-teal-700 outline-none resize-none font-medium transition">{{ old('description') }}</textarea>
                            </div>

                            <div class="flex justify-end space-x-4 pt-4">
                                <a href="{{ route('admin.product.index') }}" class="px-10 py-4 bg-white border border-gray-200 text-gray-700 font-extrabold rounded-xl hover:bg-gray-50 transition text-sm">
                                    Batal
                                </a>
                                <button type="submit" class="px-10 py-4 bg-btnprimary hover:bg-btnhover text-white font-extrabold rounded-xl shadow-md transition text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Simpan Produk Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="h-24"></div> 
        </div>
    </main>

    <script>
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
    </script>
</body>
</html>