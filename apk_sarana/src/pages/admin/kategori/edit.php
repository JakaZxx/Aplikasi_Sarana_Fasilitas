<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/kategori/{id}/edit', function (string $id) {
    require_auth();
    $errors = validation_errors();

    $stmt = db()->prepare("SELECT * FROM kategoris WHERE id_kategori = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();

    if (!$kategori) {
        session_flash('error', 'Kategori tidak ditemukan.');
        redirect('/admin/kategori');
    }

    admin_layout_start('Edit Kategori | SMKN 4 Bandung');
    ?>
    <div class="py-12 relative min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <a href="<?= url('admin/kategori') ?>" class="p-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-indigo-600 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Edit Kategori Fasilitas</h2>
            </div>
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-2xl shadow-indigo-600/10 dark:shadow-none sm:rounded-3xl p-8 ring-1 ring-indigo-600/5 dark:ring-white/10 backdrop-blur-xl">
                <form action="<?= url('admin/kategori/<?= e($id) ?>') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="space-y-2">
                        <label for="ket_kategori" class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Kategori Fasilitas</label>
                        <input type="text" name="ket_kategori" id="ket_kategori" required autofocus
                            value="<?= old('ket_kategori', $kategori['ket_kategori']) ?>"
                            class="block w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm py-4 px-5 transition-all bg-slate-50 dark:text-white"
                            placeholder="Nama kategori fasilitas">
                        <?= error_msg('ket_kategori', $errors) ?>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">Nama kategori harus unik dan mendeskripsikan kelompok fasilitas sekolah.</p>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="<?= url('admin/kategori') ?>" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Batal</a>
                        <button type="submit" class="group px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400 transition-all group-hover:w-2"></div>
                            <span class="z-10">Perbarui Kategori</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-6 rounded-3xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 flex gap-4 items-start">
                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-700 dark:text-amber-400"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                <div>
                    <h4 class="font-bold text-amber-800 dark:text-amber-400">Penting!</h4>
                    <p class="text-sm text-amber-700 dark:text-amber-500/80 leading-relaxed font-medium">Perubahan nama kategori akan langsung berdampak pada seluruh data pengaduan yang sudah menggunakan kategori ini sebelumnya.</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    admin_layout_end();
});
