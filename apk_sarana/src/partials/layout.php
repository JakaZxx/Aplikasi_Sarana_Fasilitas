<?php
// Layout functions
function layout_head(string $title = 'Sistem Pengaduan Sarana | SMKN 4 Bandung', bool $with_tailwind = true): void {
    $cdn_tw = 'https://cdn.tailwindcss.com';
    ?>
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= BASE_URL ?>/">
    <title><?= e($title) ?></title>
    <link rel="icon" href="<?= asset('logo.png') ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php if ($with_tailwind): ?>
    <script src="<?= $cdn_tw ?>"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        'smk-blue': '#0F52BA',
                        'smk-yellow': '#FFD700'
                    }
                }
            }
        }
    </script>
    <?php endif; ?>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { display: none; }
        html, body { -ms-overflow-style: none; scrollbar-width: none; }
        .bg-main-image {
            background-image: linear-gradient(rgba(248,250,252,0.8), rgba(248,250,252,0.9)), url('<?= asset('backgroundsmk.png') ?>');
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        .dark .bg-main-image {
            background-image: linear-gradient(rgba(15,23,42,0.8), rgba(15,23,42,0.9)), url('<?= asset('backgroundsmk.png') ?>');
        }
    </style>
    <?php
}

function layout_foot(): void {
    ?>
</body></html>
    <?php
}

// Admin layout
function admin_layout_start(string $title = 'Dashboard Admin | SMKN 4 Bandung'): void {
    layout_head($title);
    $active_route = $_SERVER['REQUEST_URI'] ?? '/';
    $user = auth();
    $dashboard_active = str_contains($active_route, '/admin/dashboard') ? 'border-b-2 border-smk-blue text-smk-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white';
    $kategori_active = str_contains($active_route, '/admin/kategori') ? 'border-b-2 border-smk-blue text-smk-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white';
    $users_active = str_contains($active_route, '/admin/users') ? 'border-b-2 border-smk-blue text-smk-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white';
    $siswas_active = str_contains($active_route, '/admin/siswas') ? 'border-b-2 border-smk-blue text-smk-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white';
    ?>
<body class="bg-main-image font-sans antialiased text-slate-800 dark:text-slate-200 transition-colors duration-300">
<div class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center gap-3">
                        <a href="<?= url('admin/dashboard') ?>" class="flex items-center gap-3">
                            <img src="<?= asset('logo.png') ?>" alt="Logo SMKN 4 Bandung" class="h-10 w-auto object-contain">
                            <div class="hidden sm:block">
                                <span class="font-bold text-lg tracking-tight text-slate-800 dark:text-slate-100 block leading-none mt-1">SMKN 4 Bandung</span>
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block mt-1">SMKN 4 Bandung</span>
                            </div>
                        </a>
                    </div>
                    <!-- Nav Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <a href="<?= url('admin/dashboard') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-semibold transition-colors <?= $dashboard_active ?>">Dashboard Admin</a>
                        <a href="<?= url('admin/kategori') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-semibold transition-colors <?= $kategori_active ?>">Manajemen Kategori</a>
                        <a href="<?= url('admin/users') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-semibold transition-colors <?= $users_active ?>">Manajemen Akun</a>
                        <a href="<?= url('admin/siswas') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-semibold transition-colors <?= $siswas_active ?>">Manajemen Siswa</a>
                    </div>
                </div>
                <!-- Right side -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                    <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-500 dark:text-slate-400 focus:outline-none">
                        <svg class="w-5 h-5 hidden dark:block text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" onclick="this.querySelector('[data-dropdown]').classList.toggle('hidden')">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-xl text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 hover:text-slate-900 dark:hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div class="mr-2 bg-blue-100 dark:bg-blue-900/50 p-1.5 rounded-lg text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div><?= e($user['username'] ?? '') ?></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                        <div data-dropdown class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10 z-50">
                            <a href="<?= url('profile') ?>" class="block px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-t-xl transition-colors">Profil</a>
                            <form method="POST" action="<?= url('logout') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full text-left block px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-b-xl transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu (hidden by default) -->
        <div class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1 border-t border-slate-100 dark:border-slate-700">
                <a href="<?= url('admin/dashboard') ?>" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors text-slate-600 dark:text-slate-300">Dashboard Admin</a>
                <a href="<?= url('admin/kategori') ?>" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors text-slate-600 dark:text-slate-300">Manajemen Kategori</a>
                <a href="<?= url('admin/users') ?>" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors text-slate-600 dark:text-slate-300">Manajemen Akun</a>
                <a href="<?= url('admin/siswas') ?>" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors text-slate-600 dark:text-slate-300">Manajemen Siswa</a>
            </div>
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-slate-700">
                <div class="px-4 font-medium text-base text-gray-800 dark:text-slate-200"><?= e($user['username'] ?? '') ?></div>
                <div class="mt-3 space-y-1">
                    <a href="<?= url('profile') ?>" class="block pl-3 pr-4 py-2 text-base font-medium text-slate-600 dark:text-slate-300">Profil</a>
                    <form method="POST" action="<?= url('logout') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-slate-600 dark:text-slate-300">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main>
    <?php
}

function admin_layout_end(): void {
    ?>
    </main>
</div>
</body></html>
    <?php
}

// Guest layout 
function guest_layout_start(string $title = 'Portal | SMKN 4 Bandung', string $portal_name = 'Portal', string $tagline = 'Aplikasi Pengaduan Sarana Sekolah'): void {
    layout_head($title);
    ?>
<body class="bg-main-image font-sans text-slate-900 dark:text-slate-100 antialiased transition-colors duration-300 relative">
    <div class="absolute top-5 left-5 sm:top-6 sm:left-6 flex items-center gap-3 z-20">
        <img src="<?= asset('logo.png') ?>" alt="Logo SMKN 4 Bandung" class="h-10 w-auto object-contain">
        <div class="hidden sm:block">
            <span class="font-bold text-lg tracking-tight text-slate-800 dark:text-slate-100 block leading-none mt-1">SMKN 4 Bandung</span>
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block mt-1">SMKN 4 Bandung</span>
        </div>
    </div>
    <div class="absolute top-5 right-5 sm:top-6 sm:right-6 flex gap-3 z-20">
        <button onclick="toggleTheme()" class="p-2.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-700">
            <svg class="w-5 h-5 hidden dark:block text-yellow-500 drop-shadow-[0_0_8px_rgba(255,215,0,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>
    </div>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="relative w-full max-w-md mt-6 px-6 py-8 bg-white dark:bg-slate-800 shadow-lg ring-1 ring-slate-900/5 dark:ring-slate-100/10 sm:rounded-2xl transition-colors z-10">
            <div class="relative z-10 flex flex-col items-center mb-8">
                <a href="<?= url('') ?>"><img src="<?= asset('logo.png') ?>" alt="Logo SMKN 4 Bandung" class="h-16 w-auto mb-4 object-contain"></a>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight"><?= e($portal_name) ?></h2>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1"><?= e($tagline) ?></p>
            </div>
            <div class="relative z-10">
    <?php
}

function guest_layout_end(): void {
    ?>
            </div>
        </div>
        <a href="<?= url('') ?>" class="mt-8 text-sm font-medium text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali ke Halaman Utama
        </a>
    </div>
</body></html>
    <?php
}
