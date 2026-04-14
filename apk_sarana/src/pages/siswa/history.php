<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/history', function () {
    global $_view_success;

    $nis       = get_param('nis') ?: session_get('nis');
    $aspirasis = [];

    if ($nis) {
        $stmt = db()->prepare("
            SELECT 
                ia.id_pelaporan, ia.nis, ia.id_kategori, ia.lokasi, ia.lampiran, ia.ket, ia.urgensi, ia.created_at,
                k.ket_kategori,
                a.id_aspirasi, a.status, a.feedback
            FROM input_aspirasis ia
            LEFT JOIN kategoris k ON k.id_kategori = ia.id_kategori
            LEFT JOIN aspirasis a ON a.id_pelaporan = ia.id_pelaporan
            WHERE ia.nis = ?
            ORDER BY ia.created_at DESC
        ");
        $stmt->execute([$nis]);
        $aspirasis = $stmt->fetchAll();
    }

    $success = $_view_success;

    layout_head('Riwayat Pengaduan | SMKN 4 Bandung');
    ?>
<body class="bg-main-image text-slate-800 dark:text-slate-200 min-h-screen p-6 transition-colors duration-300 relative overflow-x-hidden">

<div class="fixed top-[-10%] sm:top-[-20%] right-[-5%] w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] rounded-full bg-smk-blue/10 dark:bg-smk-blue/5 blur-[100px] pointer-events-none z-0"></div>

<nav class="absolute top-0 w-full left-0 p-5 sm:p-6 flex justify-between items-center z-20">
    <div class="flex items-center gap-3 pl-2 sm:pl-0">
        <div class="overflow-hidden p-0.5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
            <img src="<?= asset('logo.png') ?>" alt="Logo SMKN 4 Bandung" class="w-10 h-10 sm:w-11 sm:h-11 object-contain">
        </div>
        <div>
            <span class="font-bold text-lg sm:text-xl tracking-tight hidden sm:block text-slate-800 dark:text-slate-100 leading-none">SMKN 4 Bandung</span>
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider hidden sm:block mt-1">Aplikasi Sarana Fasilitas Sekolah</span>
        </div>
    </div>
    <button onclick="toggleTheme()" class="p-2.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-700 md:mr-4 mr-2">
        <svg class="w-5 h-5 hidden dark:block text-yellow-400 drop-shadow-[0_0_8px_rgba(255,215,0,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
    </button>
</nav>

<div class="max-w-4xl mx-auto pt-16 sm:pt-24 relative z-10">
    <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Pelacakan <span class="text-transparent bg-clip-text bg-gradient-to-r from-smk-blue to-blue-500">Aduan Fasilitas</span></h1>
        <a href="<?= url('') ?>" class="text-sm font-semibold text-smk-blue dark:text-blue-400 hover:text-white bg-white hover:bg-smk-blue border border-smk-blue/20 dark:border-smk-blue/40 dark:bg-slate-800 dark:hover:bg-smk-blue px-5 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all w-full sm:w-auto justify-center group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Beranda Utama
        </a>
    </div>

    <?php if ($success): ?>
    <div class="mb-8 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 flex gap-3 items-center shadow-sm">
        <div class="bg-emerald-100 dark:bg-emerald-800/50 p-1.5 rounded-full"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
        <span class="text-sm font-semibold"><?= e($success) ?></span>
    </div>
    <?php endif; ?>

    <div class="bg-white/95 dark:bg-slate-800/95 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none ring-1 ring-smk-blue/10 dark:ring-white/10 p-6 sm:p-8 mb-8 backdrop-blur-xl transition-colors">
        <form action="<?= url('history') ?>" method="GET" class="flex flex-col md:flex-row gap-5 items-end">
            <div class="flex-1 w-full space-y-1.5">
                <label for="nis" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Cek Status menggunakan NIS Anda</label>
                <input type="text" name="nis" id="nis" value="<?= e(get_param('nis') ?: $nis) ?>" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20 dark:bg-slate-900 dark:text-white sm:text-sm py-3.5 px-5 transition-colors bg-slate-50" placeholder="Ketik NIS Anda. Contoh: 2023101">
            </div>
            <button type="submit" class="w-full md:w-auto bg-smk-blue rounded-2xl py-3.5 px-10 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-smk-blue/30 overflow-hidden relative transition-all transform hover:-translate-y-0.5 hover:bg-blue-800 group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-smk-yellow transition-all group-hover:w-2"></div>
                <svg class="w-5 h-5 mr-3 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="z-10 uppercase tracking-widest text-xs">Cari Riwayat</span>
            </button>
        </form>
    </div>

    <?php if ($nis && count($aspirasis) > 0): ?>
    <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none ring-1 ring-smk-blue/10 dark:ring-white/10 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                <thead class="bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-sm">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Area &amp; Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Keterangan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Respons</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <?php foreach ($aspirasis as $item):
                        $urgency = $item['urgensi'] ?? 'Standar';
                        $urgencyColor = match($urgency) {
                            'Mendesak' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-900',
                            'Rendah'   => 'bg-sky-100 text-sky-800 border-sky-200 dark:bg-sky-900/30 dark:text-sky-400 dark:border-sky-900',
                            default    => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-900',
                        };
                        $status = $item['status'] ?? 'Menunggu';
                        $statusColor = match($status) {
                            'Menunggu' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-900',
                            'Proses'   => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-900',
                            'Selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-900',
                            default    => 'bg-slate-100 text-slate-800 border-slate-200',
                        };
                    ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="px-6 py-5 whitespace-nowrap text-sm">
                            <div class="font-bold text-slate-900 dark:text-white border-b-2 border-smk-blue/30 inline-block pb-0.5"><?= format_date($item['created_at'], 'd M Y') ?></div>
                            <div class="text-xs mt-1 font-medium text-slate-500 dark:text-slate-400"><?= date('H:i', strtotime($item['created_at'])) ?> WIB</div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap hidden sm:table-cell">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-900/50 text-xs font-bold text-slate-700 dark:text-slate-300 ring-1 ring-inset ring-slate-200 dark:ring-slate-700/60 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-smk-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg> <?= e($item['ket_kategori']) ?>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border shadow-[0_1px_2px_rgba(0,0,0,0.05)] uppercase tracking-wider <?= $urgencyColor ?>"><?= e($urgency) ?></span>
                            </div>
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> <?= e($item['lokasi']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-700 dark:text-slate-300 max-w-xs break-words leading-relaxed font-medium hidden lg:table-cell">
                            <div class="flex flex-col gap-2">
                                <span>"<?= e($item['ket']) ?>"</span>
                                <?php if ($item['lampiran']): ?>
                                <div class="mt-1">
                                    <button type="button" onclick="openImageModal('<?= asset($item['lampiran']) ?>')" class="group/img relative inline-block rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm transition-transform hover:scale-105">
                                        <img src="<?= asset($item['lampiran']) ?>" alt="Lampiran" class="w-16 h-16 object-cover bg-slate-100">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity"><svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg></div>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="px-3 py-1.5 inline-flex items-center justify-center text-xs font-bold rounded-full border <?= $statusColor ?> shadow-sm">
                                <?php if ($status === 'Proses'): ?><svg class="w-3.5 h-3.5 mr-1 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <?php elseif ($status === 'Selesai'): ?><svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <?php else: ?><svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php endif; ?>
                                <?= e($status) ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm max-w-xs break-words">
                            <?php if (!empty($item['feedback'])): ?>
                            <div class="bg-blue-50/50 dark:bg-slate-900/80 p-3.5 rounded-xl border border-blue-100 dark:border-slate-700/80 leading-relaxed font-medium text-slate-700 dark:text-slate-300 relative overflow-hidden shadow-sm">
                                <div class="absolute top-0 left-0 bottom-0 w-1 bg-smk-blue/80 dark:bg-blue-500/80 rounded-l-xl"></div>
                                "<?= e($item['feedback']) ?>"
                            </div>
                            <?php else: ?>
                            <span class="inline-flex items-center text-slate-400 dark:text-slate-500 font-medium text-xs uppercase tracking-wider bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md"><svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Menunggu Respons</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif ($nis): ?>
    <div class="text-center p-12 bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-3xl shadow-xl ring-1 ring-smk-blue/10 dark:ring-white/10 relative overflow-hidden">
        <div class="relative z-10 mx-auto w-24 h-24 bg-slate-50 dark:bg-slate-900/80 rounded-full flex items-center justify-center mb-6 shadow-inner ring-1 ring-slate-100 dark:ring-slate-800">
            <svg class="h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white relative z-10">Riwayat Pengajuan Kosong</h3>
        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400 max-w-sm mx-auto relative z-10 leading-relaxed">Kami tidak dapat menemukan laporan untuk NIS <span class="font-bold px-2 py-0.5 bg-slate-100 dark:bg-slate-900 rounded-md text-smk-blue dark:text-blue-400"><?= e($nis) ?></span> pada database sistem kami.</p>
        <div class="mt-8 relative z-10">
            <a href="<?= url('') ?>" class="inline-flex items-center px-6 py-3.5 border border-transparent shadow-lg shadow-smk-blue/30 text-sm font-bold uppercase tracking-wide rounded-xl text-white bg-smk-blue hover:bg-blue-800 transition-all group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-smk-yellow rounded-l-xl"></div>
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Laporan Baru
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Image Lightbox Modal -->
<div id="image-modal" class="hidden fixed inset-0 flex items-center justify-center bg-slate-900/90 p-4 backdrop-blur-sm" style="z-index:9999;" onclick="this.classList.add('hidden')">
    <div class="relative max-w-4xl max-h-[90vh] w-full flex items-center justify-center rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/20">
        <button type="button" class="absolute top-4 right-4 z-50 p-2 bg-black/40 hover:bg-black/60 text-white rounded-full transition-colors backdrop-blur-md" onclick="document.getElementById('image-modal').classList.add('hidden')"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        <img id="modal-image" src="" alt="Full size" class="max-w-full max-h-[90vh] object-contain">
    </div>
</div>
<script>
function openImageModal(src) {
    const modal = document.getElementById('image-modal');
    const img = document.getElementById('modal-image');
    img.src = src;
    modal.classList.remove('hidden');
}
document.querySelector('#image-modal img').onclick = (e) => e.stopPropagation();
</script>
</body></html>
    <?php
});
