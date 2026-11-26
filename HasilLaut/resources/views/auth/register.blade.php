<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pembeli | HasilLaut</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f4f8f9] text-[#013137] antialiased selection:bg-[#297376] selection:text-white">

    <div class="min-h-screen lg:grid lg:grid-cols-12">

        <!-- PANEL KIRI: Visual Brand HasilLaut (Tampil di Desktop lg+) -->
        <aside class="relative hidden lg:col-span-5 lg:flex lg:flex-col lg:justify-between overflow-hidden bg-gradient-to-br from-[#013137] via-[#215E68] to-[#297376] p-10 xl:p-12 text-white">
            <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-luminosity"
                 style="background-image: url('https://images.unsplash.com/photo-1534482498418-fef13c323f46?auto=format&fit=crop&w=1400&q=80');">
            </div>
            <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-[#5C9396]/25 blur-3xl"></div>
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-[#C1D9DE]/15 blur-3xl"></div>

            <!-- Logo Atas -->
            <div class="relative z-10">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 group">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 border border-[#C1D9DE]/30 backdrop-blur-md group-hover:bg-white/20 transition">
                        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="Logo HasilLaut" class="h-7 w-7 object-contain">
                    </div>
                    <div>
                        <span class="block text-base font-extrabold tracking-tight text-white">HASILLAUT</span>
                        <span class="block text-[11px] font-medium text-[#C1D9DE]">Belanja Hasil Laut Segar</span>
                    </div>
                </a>
            </div>

            <!-- Konten Tengah -->
            <div class="relative z-10 my-auto space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-[#C1D9DE]/30 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-[#C1D9DE] backdrop-blur-sm">
                    <i class="bi bi-stars"></i>
                    Pendaftaran Akun Pembeli
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight text-white">
                    Nikmati Tangkapan Laut Terbaik Setiap Hari.
                </h2>

                <ul class="space-y-3.5 text-xs sm:text-sm text-[#C1D9DE]/95">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#5C9396]/40 text-white">
                            <i class="bi bi-check2 text-xs"></i>
                        </span>
                        <span>Akses penuh katalog ikan segar, udang, kepiting, dan cumi dari Hub Pangandaran.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#5C9396]/40 text-white">
                            <i class="bi bi-check2 text-xs"></i>
                        </span>
                        <span>Promo <strong>Flash Sale Rantai Dingin</strong> dengan potongan harga spesial setiap hari.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#5C9396]/40 text-white">
                            <i class="bi bi-check2 text-xs"></i>
                        </span>
                        <span>Pilihan pembayaran fleksibel via <strong>QRIS / Transfer</strong> maupun <strong>COD</strong> di tempat.</span>
                    </li>
                </ul>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 flex items-center justify-between border-t border-[#C1D9DE]/20 pt-5 text-xs text-[#C1D9DE]/80">
                <span>&copy; {{ date('Y') }} HASILLAUT</span>
                <span>450+ Nelayan Mitra Pesisir</span>
            </div>
        </aside>

        <!-- PANEL KANAN: Form Register (Responsif HP & Desktop) -->
        <main class="flex min-h-screen flex-col justify-between lg:col-span-7 px-4 py-6 sm:px-8 lg:px-12 xl:px-16">

            <!-- Top Bar Mobile & Tombol Kembali -->
            <div class="mx-auto flex w-full max-w-xl items-center justify-between pt-1">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 lg:hidden">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#013137] text-white shadow-sm">
                        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="h-6 w-6 object-contain">
                    </div>
                    <span class="text-sm font-extrabold tracking-tight text-[#013137]">HASILLAUT</span>
                </a>

                <a href="{{ route('welcome') }}"
                   class="ml-auto inline-flex items-center gap-1.5 rounded-full border border-[#C1D9DE] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#215E68] hover:bg-[#C1D9DE]/25 hover:text-[#013137] transition">
                    <i class="bi bi-arrow-left"></i>
                    <span>Beranda</span>
                </a>
            </div>

            <!-- Card Form Register -->
            <div class="mx-auto my-auto w-full max-w-xl py-5">
                <div class="rounded-3xl border border-[#C1D9DE]/80 bg-white p-6 sm:p-8 shadow-[0_12px_36px_rgba(1,49,55,0.06)]">

                    <!-- Header Form -->
                    <div class="mb-5">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C1D9DE]/40 px-3 py-1 text-[11px] font-bold text-[#215E68]">
                            <i class="bi bi-bag-heart"></i> Akun Pembeli Baru
                        </span>
                        <h1 class="mt-2.5 text-2xl sm:text-[26px] font-extrabold tracking-tight text-[#013137]">
                            Buat Akun HasilLaut
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-[#5C9396]">
                            Lengkapi data diri dan alamat pengiriman untuk mulai berbelanja.
                        </p>
                    </div>

                    <!-- Info Wilayah Layanan -->
                    <div class="mb-5 flex items-start gap-2.5 rounded-2xl border border-[#5C9396]/40 bg-[#C1D9DE]/25 px-3.5 py-2.5 text-[11px] sm:text-xs text-[#013137]">
                        <i class="bi bi-geo-alt-fill mt-0.5 text-[#215E68] shrink-0"></i>
                        <span>Pengiriman aktif saat ini mencakup <strong>wilayah Pangandaran</strong> dan sekitarnya.</span>
                    </div>

                    <!-- Alert Error Validasi -->
                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-xs text-red-700" role="alert">
                            <p class="font-bold flex items-center gap-1.5">
                                <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                                Periksa kembali data pendaftaran Anda:
                            </p>
                            <ul class="mt-1.5 list-disc pl-5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Register -->
                    <form action="{{ route('register.process') }}" method="POST" id="registerForm" class="space-y-4">
                        @csrf

                        <!-- Baris 1: Nama Lengkap & Username -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-bold text-[#013137] mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                        <i class="bi bi-person-vcard text-sm"></i>
                                    </span>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus
                                           placeholder="Nama lengkap Anda"
                                           class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-9 pr-3.5 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                                </div>
                            </div>

                            <div>
                                <label for="username" class="block text-xs font-bold text-[#013137] mb-1.5">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                        <i class="bi bi-at text-base"></i>
                                    </span>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           value="{{ old('username') }}"
                                           required
                                           autocomplete="username"
                                           placeholder="Contoh: hilyashafwa"
                                           class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-9 pr-3.5 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                                </div>
                                <p class="mt-1 text-[10px] text-[#5C9396]">Huruf, angka, tanpa spasi (min. 4 karakter).</p>
                            </div>
                        </div>

                        <!-- Baris 2: Nomor Telepon / WhatsApp -->
                        <div>
                            <label for="phone_number" class="block text-xs font-bold text-[#013137] mb-1.5">
                                Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                    <i class="bi bi-whatsapp text-sm"></i>
                                </span>
                                <input type="tel"
                                       id="phone_number"
                                       name="phone_number"
                                       value="{{ old('phone_number') }}"
                                       required
                                       placeholder="Contoh: 081234567890"
                                       class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-9 pr-4 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                            </div>
                        </div>

                        <!-- Baris 3: Alamat Lengkap -->
                        <div>
                            <label for="address" class="block text-xs font-bold text-[#013137] mb-1.5">
                                Alamat Lengkap Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address"
                                      name="address"
                                      rows="2"
                                      required
                                      placeholder="Contoh: Jl. Pantai Pangandaran No. 12, RT 02 / RW 05, Kec. Pangandaran"
                                      class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] px-3.5 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">{{ old('address') }}</textarea>
                        </div>

                        <!-- Baris 4: Password & Konfirmasi Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="reg_password" class="block text-xs font-bold text-[#013137] mb-1.5">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                        <i class="bi bi-lock text-sm"></i>
                                    </span>
                                    <input type="password"
                                           id="reg_password"
                                           name="password"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Min. 6 karakter"
                                           class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-9 pr-10 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                                    <button type="button"
                                            onclick="togglePassword('reg_password', 'eyeIconReg1')"
                                            aria-label="Tampilkan password"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#5C9396] hover:text-[#013137] cursor-pointer">
                                        <i id="eyeIconReg1" class="bi bi-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-[#013137] mb-1.5">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                        <i class="bi bi-shield-lock text-sm"></i>
                                    </span>
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Ulangi password"
                                           class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-9 pr-10 py-2.5 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                                    <button type="button"
                                            onclick="togglePassword('password_confirmation', 'eyeIconReg2')"
                                            aria-label="Tampilkan konfirmasi password"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#5C9396] hover:text-[#013137] cursor-pointer">
                                        <i id="eyeIconReg2" class="bi bi-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Daftar -->
                        <button type="submit"
                                id="registerSubmitBtn"
                                class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-[#013137] hover:bg-[#215E68] active:bg-[#297376] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition cursor-pointer">
                            <span>Daftar Akun Pembeli</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <!-- Link ke Login -->
                    <p class="mt-6 text-center text-xs text-[#5C9396]">
                        Sudah punya akun HasilLaut?
                        <a href="{{ route('login') }}" class="font-extrabold text-[#215E68] hover:text-[#013137] underline decoration-[#5C9396] underline-offset-4 transition">
                            Masuk di Sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Mobile -->
            <p class="text-center text-[11px] text-[#5C9396] lg:hidden">
                &copy; {{ date('Y') }} HASILLAUT &bull; Wilayah Layanan Pangandaran
            </p>
        </main>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function () {
            const btn = document.getElementById('registerSubmitBtn');
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i><span>Membuat Akun...</span>';
        });
    </script>
</body>
</html>