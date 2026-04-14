<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/dashboard', function () {
    require_auth();
    global $_view_success;

    // Build query
    $conditions = [];
    $params     = [];

    if (get_param('tanggal') !== '') {
        $conditions[] = "DATE(ia.created_at) = ?";
        $params[]     = get_param('tanggal');
    }
    if (get_param('bulan') !== '') {
        $conditions[] = "MONTH(ia.created_at) = ?";
        $params[]     = get_param('bulan');
    }
    if (get_param('nis') !== '') {
        $conditions[] = "ia.nis = ?";
        $params[]     = get_param('nis');
    }
    if (get_param('kategori') !== '') {
        $conditions[] = "ia.id_kategori = ?";
        $params[]     = get_param('kategori');
    }
    if (get_param('urgensi') !== '') {
        $conditions[] = "ia.urgensi = ?";
        $params[]     = get_param('urgensi');
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $sql = "SELECT 
                ia.id_pelaporan, ia.nis, ia.id_kategori, ia.lokasi, ia.lampiran, ia.ket, ia.urgensi, ia.created_at,
                k.ket_kategori,
                a.id_aspirasi, a.status, a.feedback
            FROM input_aspirasis ia
            LEFT JOIN kategoris k ON k.id_kategori = ia.id_kategori
            LEFT JOIN aspirasis a ON a.id_pelaporan = ia.id_pelaporan
            $where
            ORDER BY ia.created_at DESC";

    $paged    = paginate($sql, $params, 10);
    $aspirasis = $paged['data'];

    $kategoris = db()->query("SELECT id_kategori, ket_kategori FROM kategoris ORDER BY ket_kategori ASC")->fetchAll();

    $success = $_view_success;
    $extra_q = array_filter([
        'tanggal'  => get_param('tanggal'),
        'bulan'    => get_param('bulan'),
        'nis'      => get_param('nis'),
        'kategori' => get_param('kategori'),
        'urgensi'  => get_param('urgensi'),
    ], fn($v) => $v !== '');

    $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

  
    $list_status = [
        'Menunggu' => 'Menunggu Pengecekan (0%)',
        'Proses'   => 'Sedang Diperbaiki Teknisi (50%)',
        'Selesai'  => 'Masalah Tertangani Selesai (100%)'
    ];

    admin_layout_start('Dashboard Admin | SMKN 4 Bandung');
    ?>
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700"><svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                    <span class="text-xl font-extrabold tracking-tight">Dashboard Admin Pengaduan</span>
                </h2>
            </div>

            <?php if ($success): ?>
            <div class="mb-4 p-4 text-sm font-semibold text-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center gap-2 shadow-sm">
                <div class="bg-emerald-100 dark:bg-emerald-800/50 p-1 rounded-full"><svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                <?= e($success) ?>
            </div>
            <?php endif; ?>

            <!-- Filter Card -->
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-3xl p-6 md:p-8 ring-1 ring-smk-blue/5 dark:ring-white/10 transition-colors backdrop-blur-xl mb-6">
                <div class="mb-8 pb-4 border-b border-slate-100 dark:border-slate-700/80">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Pusat Filter Data</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Saring rekap keluhan yang masuk demi kemudahan klasifikasi</p>
                </div>
                <form action="<?= url('admin/dashboard') ?>" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Tanggal Harian</label>
                            <input type="date" name="tanggal" value="<?= e(get_param('tanggal')) ?>" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm focus:border-smk-blue sm:text-xs py-2 px-3 transition-colors bg-slate-50 dark:text-white">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Periode Bulan</label>
                            <select name="bulan" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-xs py-2 px-3 transition-colors bg-slate-50 cursor-pointer dark:text-white">
                                <option value="">Semua Bulan</option>
                                <?php foreach ($bulanIndo as $m => $nb): ?>
                                <option value="<?= $m ?>" <?= get_param('bulan') == $m ? 'selected' : '' ?>><?= $nb ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Pelapor (NIS)</label>
                            <input type="text" name="nis" value="<?= e(get_param('nis')) ?>" placeholder="Ex: 2023101" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-xs py-2 px-3 transition-colors bg-slate-50 dark:text-white">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Kategori Fasilitas</label>
                            <select name="kategori" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-xs py-2 px-3 transition-colors bg-slate-50 cursor-pointer dark:text-white">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($kategoris as $kat): ?>
                                <option value="<?= e($kat['id_kategori']) ?>" <?= get_param('kategori') == $kat['id_kategori'] ? 'selected' : '' ?>><?= e($kat['ket_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Urgensi</label>
                            <select name="urgensi" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-xs py-2 px-3 transition-colors bg-slate-50 cursor-pointer dark:text-white">
                                <option value="">Semua</option>
                                <option value="Mendesak" <?= get_param('urgensi') == 'Mendesak' ? 'selected' : '' ?>>Mendesak</option>
                                <option value="Standar" <?= get_param('urgensi') == 'Standar' ? 'selected' : '' ?>>Standar</option>
                                <option value="Rendah" <?= get_param('urgensi') == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="group flex-1 bg-smk-blue hover:bg-blue-800 rounded-xl py-3 px-4 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-smk-blue/30 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-smk-yellow transition-all group-hover:w-2"></div>
                            <svg class="w-4 h-4 mr-2 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span class="z-10 uppercase tracking-wider text-xs">Filter Data</span>
                        </button>
                        <a href="<?= url('admin/export-pdf?<?= http_build_query($extra_q) ?>') ?>" target="_blank" class="group flex-1 bg-emerald-600 hover:bg-emerald-700 rounded-xl py-3 px-4 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400 transition-all group-hover:w-2"></div>
                            <svg class="w-4 h-4 mr-2 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="z-10 uppercase tracking-wider text-xs whitespace-nowrap">Cetak PDF</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-3xl ring-1 ring-smk-blue/5 dark:ring-white/10 overflow-hidden transition-colors backdrop-blur-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Identitas Pelapor &amp; Waktu</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Lokasi &amp; Tipe Fasilitas</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aduan Masuk (Foto &amp; Deskripsi)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Progress Lapangan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            <?php if (empty($aspirasis)): ?>
                            <tr><td colspan="5" class="px-6 py-16 text-center">
                                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">Server Pengaduan Kosong</p>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tidak ada pengaduan fasilitas yang masuk berdasarkan filter saat ini.</p>
                            </td></tr>
                            <?php else: ?>
                            <?php foreach ($aspirasis as $item):
                                $urgency      = $item['urgensi'] ?? 'Standar';
                                $urgencyColor = match($urgency) {
                                    'Mendesak' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-900',
                                    'Rendah'   => 'bg-sky-100 text-sky-800 border-sky-200 dark:bg-sky-900/30 dark:text-sky-400 dark:border-sky-900',
                                    default    => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-900',
                                };
                                $status      = $item['status'] ?? 'Menunggu';
                                $statusColor = match($status) {
                                    'Menunggu' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-900',
                                    'Proses'   => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/40 dark:text-blue-400 dark:border-blue-800',
                                    'Selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-900',
                                    default    => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-extrabold text-slate-900 dark:text-white border-b-2 border-smk-blue/30 inline-block pb-0.5"><?= e($item['nis']) ?></div>
                                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1"><?= format_datetime($item['created_at']) ?></div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium"><?= diff_for_humans($item['created_at']) ?></div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-900/50 text-xs font-bold text-slate-700 dark:text-slate-300 ring-1 ring-inset ring-slate-200 dark:ring-slate-700/60 shadow-sm"><svg class="w-3.5 h-3.5 text-smk-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg> <?= e($item['ket_kategori'] ?? '-') ?></span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border uppercase tracking-wider <?= $urgencyColor ?>"><?= e($urgency) ?></span>
                                    </div>
                                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> <?= e($item['lokasi']) ?></div>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-700 dark:text-slate-300 max-w-xs break-words leading-relaxed font-medium">
                                    <div class="flex flex-col gap-2">
                                        <span>"<?= e($item['ket']) ?>"</span>
                                        <?php if ($item['lampiran']): ?>
                                        <button type="button" onclick="openImageModal('<?= asset($item['lampiran']) ?>')" class="group/img relative inline-block rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm transition-transform hover:scale-105 w-16">
                                            <img src="<?= asset($item['lampiran']) ?>" alt="Lampiran" class="w-16 h-16 object-cover bg-slate-100">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity"><svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg></div>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1.5 inline-flex items-center justify-center text-xs font-bold rounded-full border <?= $statusColor ?> shadow-sm">
                                        <?php if ($status === 'Proses'): ?><svg class="w-3.5 h-3.5 mr-1 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <?php elseif ($status === 'Selesai'): ?><svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <?php else: ?><svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <?php endif; ?>
                                        <?= e($status) ?>
                                    </span>
                                    <?php if ($item['feedback']): ?>
                                    <div class="mt-3 text-xs leading-tight font-medium text-slate-600 dark:text-slate-400 italic break-words max-w-xs p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                                        <span class="block font-bold not-italic text-[10px] uppercase tracking-wider text-smk-blue dark:text-blue-400 mb-1">Respons Teknisi:</span>
                                        <?= e(str_limit($item['feedback'], 60)) ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm">
                                    <?php if ($status !== 'Selesai'): ?>
                                    <button onclick="document.getElementById('modal-<?= $item['id_pelaporan'] ?>').classList.remove('hidden')" class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-smk-blue hover:text-white border border-smk-blue/30 hover:border-smk-blue dark:border-slate-700 hover:bg-smk-blue dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white transition-all transform hover:-translate-y-0.5 font-bold shadow-sm group/btn">
                                        <svg class="w-4 h-4 transition-transform group-hover/btn:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span class="ml-2 mt-[1px]">Tanggapi Laporan</span>
                                    </button>
                                    <?php else: ?>
                                    <span class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 font-bold text-sm cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="ml-2 mt-[1px]">Tuntas</span>
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($paged['last_page'] > 1): ?>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/50">
                    <?= render_pagination($paged, $extra_q) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php foreach ($aspirasis as $item):
        $status = $item['status'] ?? 'Menunggu';
    ?>
    <div id="modal-<?= $item['id_pelaporan'] ?>" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 transition-opacity backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg w-full max-w-md max-h-[95vh] overflow-y-auto p-6 sm:p-8 ring-1 ring-slate-200 dark:ring-slate-700 transition-colors">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <div class="bg-smk-blue/10 dark:bg-blue-900/30 p-1.5 rounded-lg text-smk-blue dark:text-blue-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                    Kelola Aspirasi
                </h3>
                <button type="button" onclick="document.getElementById('modal-<?= $item['id_pelaporan'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 dark:bg-slate-900 dark:hover:bg-red-900/30 p-2 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="<?= url('admin/action/' . ($item['id_aspirasi'] ?? '')) ?>" method="POST" class="text-left">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="mb-5 space-y-1.5">
                    <label class="block text-sm font-bold tracking-wide text-slate-700 dark:text-slate-300">Perbarui Status Progres</label>

                    <select name="status" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-sm appearance-none py-3 pl-4 pr-10 transition-colors bg-slate-50 cursor-pointer">
                        <?php foreach ($list_status as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $status == $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="mb-6 space-y-1.5">
                    <label class="block text-sm font-bold tracking-wide text-slate-700 dark:text-slate-300">Catatan Tindakan (Feedback)</label>
                    <textarea name="feedback" rows="4" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-sm p-3.5 transition-colors resize-none bg-slate-50" placeholder="Tuliskan catatan detail dari lapangan secara singkat..."><?= e($item['feedback'] ?? '') ?></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pb-2">
                    <button type="button" onclick="document.getElementById('modal-<?= $item['id_pelaporan'] ?>').classList.add('hidden')" class="w-full sm:w-auto px-5 py-3 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup Batal</button>
                    <button type="submit" class="group w-full sm:w-auto px-6 py-3 rounded-xl shadow-lg shadow-smk-blue/30 text-sm font-bold text-white bg-smk-blue hover:bg-blue-800 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-smk-yellow transition-all group-hover:w-2"></div>
                        <span class="z-10 relative">Simpan &amp; Publikasikan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>


    <div id="image-modal" class="hidden fixed inset-0 flex items-center justify-center bg-slate-900/90 p-4 backdrop-blur-sm" style="z-index:9999;" onclick="this.classList.add('hidden')">
        <div class="relative max-w-4xl max-h-[90vh] w-full flex items-center justify-center rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/20">
            <button type="button" class="absolute top-4 right-4 z-50 p-2 bg-black/40 hover:bg-black/60 text-white rounded-full transition-colors backdrop-blur-md" onclick="document.getElementById('image-modal').classList.add('hidden')"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            <img id="modal-image" src="" alt="Full size" class="max-w-full max-h-[90vh] object-contain">
        </div>
    </div>
    <script>
    function openImageModal(src) {
        document.getElementById('modal-image').src = src;
        document.getElementById('image-modal').classList.remove('hidden');
    }
    document.querySelector('#image-modal img').onclick = (e) => e.stopPropagation();
    </script>
    <?php
    admin_layout_end();
});
