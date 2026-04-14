<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/', function () {
    global $_view_errors, $_view_success;
    require_siswa();
    $siswa = auth_siswa();

    $stmt = db()->query("SELECT id_kategori, ket_kategori FROM kategoris ORDER BY ket_kategori ASC");
    $kategoris = $stmt->fetchAll();

    layout_head('Sistem Pengaduan Sarana | SMKN 4 Bandung');

    $errors = $_view_errors;
    $success = $_view_success;
    ?>

    <body
        class="bg-main-image text-slate-800 dark:text-slate-200 antialiased min-h-screen transition-colors duration-300 relative overflow-x-hidden"
        style="background-image:linear-gradient(rgba(248,250,252,0.85),rgba(248,250,252,0.95)),url('<?= asset('backgroundsmk.png') ?>');background-size:cover;background-position:center;background-attachment:fixed;">
        <style>
            .dark body,
            .dark .bg-main-image {
                background-image: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('<?= asset('backgroundsmk.png') ?>') !important;
            }

            .bg-pattern {
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .dark .bg-pattern {
                background-image: radial-gradient(#334155 1px, transparent 1px);
            }
        </style>

        <nav class="absolute top-0 w-full p-5 sm:p-6 flex justify-between items-center z-20">
            <div class="flex items-center gap-3">
                <img src="<?= asset('logo.png') ?>" alt="Logo SMKN 4 Bandung" class="h-10 w-auto object-contain">
                <div class="hidden sm:block">
                    <span class="font-bold text-lg sm:text-xl tracking-tight text-slate-800 dark:text-slate-100 block leading-none mt-1">SMKN 4 Bandung</span>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block mt-1">SMKN 4 Bandung</span>
                </div>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden sm:block text-sm font-bold text-slate-700 dark:text-slate-200">Selamat datang,
                    <?= e($siswa['nama'] ?? '') ?></div>
                <form action="<?= url('/siswa/logout') ?>" method="POST" class="inline m-0 p-0">
                    <?= csrf_field() ?>
                    <button type="submit"
                        class="text-xs font-bold bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 py-2 px-3 rounded-lg transition-colors shadow-sm">Log
                        Out</button>
                </form>
                <button onclick="toggleTheme()"
                    class="p-2.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-700">
                    <svg class="w-5 h-5 hidden dark:block text-yellow-400 drop-shadow-[0_0_8px_rgba(255,215,0,0.5)]"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </nav>

        <div class="relative flex min-h-screen flex-col justify-center py-16 sm:py-20 z-10 bg-pattern">
            <div class="relative w-full max-w-2xl mx-auto px-4 sm:px-6">
                <div
                    class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-2xl shadow-2xl ring-1 ring-smk-blue/10 dark:ring-white/10 rounded-3xl p-8 sm:p-12 transition-all">

                    <div class="text-center mb-10">
                        <div
                            class="inline-flex py-1 px-3 mb-4 rounded-full bg-smk-blue/10 dark:bg-smk-blue/20 text-smk-blue dark:text-blue-400 text-xs font-semibold tracking-wide uppercase box-border border border-smk-blue/20 dark:border-smk-blue/30">
                            SMKN 4 Bandung</div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold mb-3 text-slate-900 dark:text-white leading-tight">
                            Sistem Aduan <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-smk-blue to-blue-500">Fasilitas</span>
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">Mari laporkan fasilitas yang
                            rusak agar tercipta lingkungan belajar yang nyaman bersama SMKN 4 Bandung.</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="mb-8 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 flex gap-3 items-center shadow-sm"
                            role="alert">
                            <div class="bg-emerald-100 dark:bg-emerald-800/50 p-1.5 rounded-full"><svg
                                    class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                    </path>
                                </svg></div>
                            <span class="text-sm font-semibold"><?= e($success) ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= url('siswa') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5 w-full">
                                <label for="nis"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Induk Siswa
                                    (NIS)</label>
                                <input type="text" name="nis" id="nis" required readonly
                                    class="block w-full rounded-xl border border-transparent shadow-none focus:ring-0 dark:bg-slate-900/50 dark:text-slate-400 cursor-not-allowed bg-slate-100 py-2.5 px-3 opacity-80 cursor-not-allowed"
                                    value="<?= e($siswa['nis'] ?? '') ?>">
                                <?= error_msg('nis', $errors) ?>
                            </div>
                            <div class="space-y-1.5 w-full">
                                <label for="kelas"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kelas /
                                    Jurusan</label>
                                <input type="text" name="kelas" id="kelas" required readonly
                                    class="block w-full rounded-xl border border-transparent shadow-none focus:ring-0 dark:bg-slate-900/50 dark:text-slate-400 cursor-not-allowed bg-slate-100 py-2.5 px-3 opacity-80 cursor-not-allowed"
                                    value="<?= e($siswa['kelas'] ?? '') ?>">
                                <?= error_msg('kelas', $errors) ?>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="id_kategori"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori
                                Fasilitas</label>
                            <select name="id_kategori" id="id_kategori" required
                                class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20 dark:bg-slate-900 dark:text-white transition-colors bg-slate-50 py-2.5 px-3 cursor-pointer">
                                <option value="">-- Pilih Jenis Fasilitas --</option>
                                <?php foreach ($kategoris as $kat): ?>
                                    <option value="<?= e($kat['id_kategori']) ?>" <?= old('id_kategori') == $kat['id_kategori'] ? 'selected' : '' ?>><?= e($kat['ket_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= error_msg('id_kategori', $errors) ?>
                        </div>

                        <div class="space-y-1.5">
                            <label for="lokasi"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Lokasi Detail
                                Ruangan</label>
                            <input type="text" name="lokasi" id="lokasi" required
                                class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20 dark:bg-slate-900 dark:text-white transition-colors bg-slate-50 py-2.5 px-3"
                                placeholder="Contoh: Gedung B Ruang Teori RPL" value="<?= old('lokasi') ?>">
                            <?= error_msg('lokasi', $errors) ?>
                        </div>

                        <div class="space-y-1.5">
                            <label for="ket"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Deskripsi
                                Kondisi</label>
                            <textarea name="ket" id="ket" required rows="3"
                                class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20 dark:bg-slate-900 dark:text-white transition-colors bg-slate-50 py-3 px-3 resize-none"
                                placeholder="Jelaskan secara singkat kondisi barang yang rusak..."><?= old('ket') ?></textarea>
                            <?= error_msg('ket', $errors) ?>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Foto Bukti
                                (Lampiran)</label>
                            <div class="relative group">
                                <input type="file" name="lampiran" id="lampiran" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    onchange="previewImage(event)">
                                <div id="dropzone"
                                    class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 text-center transition-all group-hover:border-smk-blue group-hover:bg-blue-50/30 dark:group-hover:bg-blue-900/10">
                                    <div id="preview-container" class="hidden mb-4"><img id="image-preview" src="#"
                                            alt="Preview"
                                            class="mx-auto h-32 w-auto rounded-lg object-cover shadow-md ring-2 ring-smk-blue/20">
                                    </div>
                                    <div id="upload-prompt" class="space-y-2">
                                        <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-smk-blue transition-colors"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div class="text-sm"><span class="font-bold text-smk-blue">Pilih foto</span> atau
                                            seret ke sini</div>
                                        <p
                                            class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold">
                                            PNG, JPG, WEBP (Maks. 5MB)</p>
                                    </div>
                                    <div id="file-info"
                                        class="hidden mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        <span id="filename"></span></div>
                                </div>
                            </div>
                            <?= error_msg('lampiran', $errors) ?>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tingkat Urgensi
                                Kerusakan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="urgensi" value="Mendesak" class="peer sr-only" required
                                        <?= old('urgensi') == 'Mendesak' ? 'checked' : '' ?>>
                                    <div
                                        class="relative px-4 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-center peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/30 transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <div class="text-red-600 dark:text-red-400 font-bold text-sm mb-1 mt-1">Mendesak
                                        </div>
                                        <div
                                            class="text-[10px] text-slate-500 dark:text-slate-300 font-medium leading-tight">
                                            Membutuhkan penanganan segera</div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="urgensi" value="Standar" class="peer sr-only" required
                                        <?= (old('urgensi', 'Standar') == 'Standar') ? 'checked' : '' ?>>
                                    <div
                                        class="relative px-4 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-center peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/30 transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <div class="text-amber-600 dark:text-amber-400 font-bold text-sm mb-1 mt-1">Standar
                                        </div>
                                        <div
                                            class="text-[10px] text-slate-500 dark:text-slate-300 font-medium leading-tight">
                                            Kerusakan fungsional untuk tindak lanjut rutin</div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="urgensi" value="Rendah" class="peer sr-only" required
                                        <?= old('urgensi') == 'Rendah' ? 'checked' : '' ?>>
                                    <div
                                        class="relative px-4 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-center peer-checked:border-sky-500 peer-checked:bg-sky-50 dark:peer-checked:bg-sky-900/30 transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <div class="text-sky-600 dark:text-sky-400 font-bold text-sm mb-1 mt-1">Rendah</div>
                                        <div
                                            class="text-[10px] text-slate-500 dark:text-slate-300 font-medium leading-tight">
                                            Kendala minor</div>
                                    </div>
                                </label>
                            </div>
                            <?= error_msg('urgensi', $errors) ?>
                        </div>

                        <button type="submit"
                            class="group mt-4 w-full flex items-center justify-center gap-2 rounded-xl bg-smk-blue px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-smk-blue/30 overflow-hidden relative transition-all focus:outline-none focus:ring-2 focus:ring-smk-blue focus:ring-offset-2 dark:focus:ring-offset-slate-900 transform hover:-translate-y-0.5 hover:bg-blue-800">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-smk-yellow transition-all group-hover:w-2">
                            </div>
                            <svg class="w-5 h-5 z-10 transition-transform group-hover:rotate-12" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span class="z-10 tracking-wide uppercase">Kirim Aspirasi Sekarang</span>
                        </button>
                    </form>

                    <div
                        class="mt-8 pt-6 flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-5 border-t border-slate-100 dark:border-slate-700">
                        <a href="<?= url('history') ?>"
                            class="group px-4 py-2 bg-slate-100 dark:bg-slate-700/50 rounded-lg text-sm font-semibold text-smk-blue dark:text-blue-400 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pantau Status Laporan
                        </a>
                        <a href="<?= url('login') ?>"
                            class="text-sm font-medium text-slate-500 border-b border-transparent hover:border-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors pb-0.5">Akses
                            Portal Admin</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function compressImage(file, maxWidth, quality) {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const img = new Image();
                        img.onload = function () {
                            const canvas = document.createElement('canvas');
                            let width = img.width, height = img.height;
                            if (width > maxWidth) { height = Math.round((height * maxWidth) / width); width = maxWidth; }
                            canvas.width = width; canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            canvas.toBlob((blob) => {
                                const ext = file.name.split('.').pop().toLowerCase();
                                const newFileName = file.name.replace('.' + ext, '.jpeg');
                                const newFile = new File([blob], newFileName, { type: 'image/jpeg', lastModified: Date.now() });
                                resolve({ file: newFile, url: canvas.toDataURL('image/jpeg', quality) });
                            }, 'image/jpeg', quality);
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            async function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('preview-container');
                const prompt = document.getElementById('upload-prompt');
                const info = document.getElementById('file-info');
                const filename = document.getElementById('filename');
                if (input.files && input.files[0]) {
                    const originalFile = input.files[0];
                    try {
                        const result = await compressImage(originalFile, 1200, 0.75);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(result.file);
                        input.files = dataTransfer.files;
                        preview.src = result.url;
                        container.classList.remove('hidden');
                        prompt.classList.add('hidden');
                        info.classList.remove('hidden');
                        const origSize = (originalFile.size / 1024 / 1024).toFixed(2);
                        const newSize = (result.file.size / 1024 / 1024).toFixed(2);
                        filename.innerHTML = `Terlampir: ${result.file.name}<br><span class="text-[10px] text-slate-500 font-normal">(Terkompresi dari ${origSize}MB menjadi ${newSize}MB)</span>`;
                    } catch (error) {
                        alert('Gagal mengompres gambar. Mohon gunakan foto yang lebih kecil.');
                        input.value = '';
                    }
                }
            }
        </script>
    </body>

    </html>
    <?php
});
