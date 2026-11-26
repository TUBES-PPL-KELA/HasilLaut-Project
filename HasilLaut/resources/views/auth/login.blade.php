<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | HasilLaut</title>
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
            <!-- Background Foto Laut + Overlay -->
            <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-luminosity"
                 style="background-image: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1400&q=80');">
            </div>
            <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[#5C9396]/25 blur-3xl"></div>
            <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-[#C1D9DE]/15 blur-3xl"></div>

            <!-- Logo Atas -->
            <div class="relative z-10">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 group">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 border border-[#C1D9DE]/30 backdrop-blur-md group-hover:bg-white/20 transition">
                        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="Logo HasilLaut" class="h-7 w-7 object-contain">
                    </div>
                    <div>
                        <span class="block text-base font-extrabold tracking-tight text-white">HASILLAUT</span>
                        <span class="block text-[11px] font-medium text-[#C1D9DE]">Cold Chain B2C Platform</span>
                    </div>
                </a>
            </div>

            <!-- Konten Tengah -->
            <div class="relative z-10 my-auto space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-[#C1D9DE]/30 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-[#C1D9DE] backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-[#C1D9DE] animate-pulse"></span>
                    HasilLaut Hub Pangandaran
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight text-white">
                    Segar dari Pesisir, Langsung ke Meja Anda.
                </h2>

                <p class="text-sm leading-relaxed text-[#C1D9DE]/90">
                    Satu pintu akses untuk pelanggan, pengelola gudang rantai dingin, dan kurir pengiriman lokal HasilLaut.
                </p>

                <!-- Kartu Keunggulan -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="rounded-2xl border border-[#C1D9DE]/20 bg-[#013137]/40 p-4 backdrop-blur-md">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#5C9396]/30 text-[#C1D9DE] mb-2.5">
                            <i class="bi bi-snow2"></i>
                        </div>
                        <p class="text-xs font-bold text-white">Rantai Dingin 100%</p>
                        <p class="mt-0.5 text-[11px] text-[#C1D9DE]/80">Kesegaran terjaga dengan metode FIFO.</p>
                    </div>

                    <div class="rounded-2xl border border-[#C1D9DE]/20 bg-[#013137]/40 p-4 backdrop-blur-md">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#5C9396]/30 text-[#C1D9DE] mb-2.5">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <p class="text-xs font-bold text-white">Harga Adil Nelayan</p>
                        <p class="mt-0.5 text-[11px] text-[#C1D9DE]/80">Distribusi langsung tanpa perantara.</p>
                    </div>
                </div>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 flex items-center justify-between border-t border-[#C1D9DE]/20 pt-5 text-xs text-[#C1D9DE]/80">
                <span>&copy; {{ date('Y') }} HASILLAUT</span>
                <span>Layanan Wilayah Pangandaran</span>
            </div>
        </aside>

        <!-- PANEL KANAN: Form Login (Responsif HP & Desktop) -->
        <main class="flex min-h-screen flex-col justify-between lg:col-span-7 px-4 py-6 sm:px-8 lg:px-14 xl:px-20">

            <!-- Top Bar Mobile & Tombol Kembali -->
            <div class="mx-auto flex w-full max-w-md items-center justify-between pt-1">
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

            <!-- Card Form Login -->
            <div class="mx-auto my-auto w-full max-w-md py-6">
                <div class="rounded-3xl border border-[#C1D9DE]/80 bg-white p-6 sm:p-9 shadow-[0_12px_36px_rgba(1,49,55,0.06)]">

                    <!-- Header Form -->
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C1D9DE]/40 px-3 py-1 text-[11px] font-bold text-[#215E68]">
                            <i class="bi bi-person-badge"></i> Portal Akses Pengguna
                        </span>
                        <h1 class="mt-3 text-2xl sm:text-[26px] font-extrabold tracking-tight text-[#013137]">
                            Selamat Datang Kembali
                        </h1>
                        <p class="mt-1.5 text-xs sm:text-sm text-[#5C9396] leading-relaxed">
                            Masuk menggunakan <strong class="text-[#215E68]">username</strong> dan password akun Anda.
                        </p>
                    </div>

                    <!-- Alert Error Login -->
                    @if ($errors->any())
                        <div class="mb-5 flex items-start gap-2.5 rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-xs text-red-700" role="alert">
                            <i class="bi bi-exclamation-circle-fill mt-0.5 text-sm text-red-600 shrink-0"></i>
                            <div>
                                <p class="font-bold">Gagal Masuk</p>
                                <p class="mt-0.5">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Alert Sukses (misal setelah logout/info) -->
                    @if (session('success'))
                        <div class="mb-5 flex items-start gap-2.5 rounded-2xl border border-[#5C9396]/40 bg-[#C1D9DE]/30 px-4 py-3 text-xs text-[#013137]">
                            <i class="bi bi-check-circle-fill mt-0.5 text-sm text-[#215E68] shrink-0"></i>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('login.process') }}" method="POST" id="loginForm" class="space-y-4">
                        @csrf

                        <!-- Input Username -->
                        <div>
                            <label for="username" class="block text-xs font-bold text-[#013137] mb-1.5">
                                Username
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                    <i class="bi bi-person text-base"></i>
                                </span>
                                <input type="text"
                                       id="username"
                                       name="username"
                                       value="{{ old('username') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       placeholder="Masukkan username Anda"
                                       class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-10 pr-4 py-3 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                            </div>
                        </div>

                        <!-- Input Password + Toggle Lihat Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-[#013137] mb-1.5">
                                Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5C9396]">
                                    <i class="bi bi-lock text-base"></i>
                                </span>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       placeholder="Masukkan password"
                                       class="w-full rounded-xl border border-[#C1D9DE] bg-[#f9fbfb] pl-10 pr-11 py-3 text-base sm:text-sm text-[#013137] placeholder-[#5C9396]/70 transition focus:border-[#215E68] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#5C9396]/20">
                                <button type="button"
                                        onclick="togglePassword('password', 'eyeIconLogin')"
                                        aria-label="Tampilkan atau sembunyikan password"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#5C9396] hover:text-[#013137] transition cursor-pointer">
                                    <i id="eyeIconLogin" class="bi bi-eye text-base"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="inline-flex items-center gap-2 text-xs font-medium text-[#215E68] cursor-pointer select-none">
                                <input type="checkbox"
                                       name="remember"
                                       class="h-4 w-4 rounded border-[#C1D9DE] text-[#215E68] focus:ring-[#5C9396]">
                                <span>Ingat sesi masuk saya</span>
                            </label>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit"
                                id="loginSubmitBtn"
                                class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-[#013137] hover:bg-[#215E68] active:bg-[#297376] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition cursor-pointer">
                            <span>Masuk ke Akun</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <!-- Info Role 1 Pintu -->
                    <div class="mt-6 rounded-2xl bg-[#f4f8f9] border border-[#C1D9DE]/60 p-3.5">
                        <p class="text-[11px] font-semibold text-[#215E68] flex items-center gap-1.5">
                            <i class="bi bi-info-circle-fill text-[#297376]"></i>
                            Sistem otomatis mengarahkan sesuai peran akun:
                        </p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span class="rounded-lg bg-white border border-[#C1D9DE] px-2.5 py-1 text-[10px] font-bold text-[#013137]">Pembeli &rarr; Katalog</span>
                            <span class="rounded-lg bg-white border border-[#C1D9DE] px-2.5 py-1 text-[10px] font-bold text-[#215E68]">Admin &rarr; Dashboard</span>
                            <span class="rounded-lg bg-white border border-[#C1D9DE] px-2.5 py-1 text-[10px] font-bold text-[#297376]">Kurir &rarr; Tugas Antar</span>
                        </div>
                    </div>

                    <!-- Link ke Register -->
                    <p class="mt-6 text-center text-xs text-[#5C9396]">
                        Belum memiliki akun pembeli?
                        <a href="{{ route('register') }}" class="font-extrabold text-[#215E68] hover:text-[#013137] underline decoration-[#5C9396] underline-offset-4 transition">
                            Daftar Sekarang
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

        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginSubmitBtn');
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i><span>Memproses...</span>';
        });
    </script>
</body>
</html>