<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/kategori/create', function () {
    require_auth();
    $errors = validation_errors();

    admin_layout_start('Tambah Kategori | SMKN 4 Bandung');
    ?>
    <div class="py-12 relative min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <a href="<?= url('admin/kategori') ?>" class="p-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-indigo-600 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Tambah Kategori Baru</h2>
            </div>
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-2xl shadow-indigo-600/10 dark:shadow-none sm:rounded-3xl p-8 ring-1 ring-indigo-600/5 dark:ring-white/10 backdrop-blur-xl">
                <form action="<?= url('admin/kategori') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    <div class="space-y-2">
                        <label for="ket_kategori" class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Kategori Fasilitas</label>
                        <input type="text" name="ket_kategori" id="ket_kategori" required autofocus value="<?= old('ket_kategori') ?>"
                            class="block w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm py-4 px-5 transition-all bg-slate-50 dark:text-white"
                            placeholder="Contoh: Alat Kebersihan, Perangkat IT, Furnitur">
                        <?= error_msg('ket_kategori', $errors) ?>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">Nama kategori harus unik dan mendeskripsikan kelompok fasilitas sekolah.</p>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="<?= url('admin/kategori') ?>" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Batal</a>
                        <button type="submit" class="group px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400 transition-all group-hover:w-2"></div>
                            <span class="z-10">Simpan Kategori</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    admin_layout_end();
});
