<?php
route('GET', '/admin/export-pdf', function () {
    require_auth();

    // Query data yang sama dengan dashboard
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
    $sql   = "SELECT 
                ia.id_pelaporan, ia.nis, ia.id_kategori, ia.lokasi, ia.ket, ia.urgensi, ia.created_at,
                k.ket_kategori,
                a.status, a.feedback
            FROM input_aspirasis ia
            LEFT JOIN kategoris k ON k.id_kategori = ia.id_kategori
            LEFT JOIN aspirasis a ON a.id_pelaporan = ia.id_pelaporan
            $where
            ORDER BY ia.created_at DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $aspirasis = $stmt->fetchAll();

    $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

    $bulan_param = get_param('bulan') ? (int)get_param('bulan') : 0;
    $periode_label = get_param('tanggal')
        ? 'Harian (' . date('d F Y', strtotime(get_param('tanggal'))) . ')'
        : ($bulan_param ? 'Bulanan (' . ($bulanIndo[$bulan_param] ?? '') . ')' : 'Semua Periode');

    $printed_by   = auth()['username'] ?? 'Admin';
    $printed_date = date('d F Y');

    // Render sebagai HTML buat di-print ke PDF lewat browser
    header('Content-Type: text/html; charset=utf-8');
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan Fasilitas - SMKN 4 Bandung</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.5; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { font-size: 18px; text-transform: uppercase; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }
        .report-title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        .info table { width: 100%; margin-bottom: 20px; }
        .info td { padding: 2px 4px; }
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.main-table th, table.main-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
        table.main-table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .status { padding: 2px 6px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 9px; display: inline-block; }
        .status-menunggu { background-color: #fef3c7; color: #92400e; }
        .status-proses { background-color: #dbeafe; color: #1e40af; }
        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 60px; display: inline-block; text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 5px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="no-print" style="margin-bottom:20px; padding:10px; background:#f0f0f0; border-radius:4px; display:flex; gap:12px; align-items:center;">
    <button onclick="window.print()" style="padding:8px 16px; background:#0F52BA; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">🖨 Cetak / Simpan PDF</button>
    <button onclick="window.history.back()" style="padding:8px 16px; background:#666; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">← Kembali</button>
    <span style="font-size:11px;color:#666;">Gunakan Ctrl+P atau tombol cetak untuk menyimpan sebagai PDF</span>
</div>

<div class="header">
    <h1>SMK NEGERI 4 BANDUNG</h1>
    <p>Jl. Kliningan No.6, Turangga, Kec. Lengkong, Kota Bandung, Jawa Barat 40264</p>
    <p>Email: info@smkn4bdg.sch.id | Telp: (022) 7303736</p>
</div>

<div class="report-title">LAPORAN REKAPITULASI PENGADUAN FASILITAS SEKOLAH</div>

<div class="info">
    <table>
        <tr>
            <td width="15%">Dicetak Oleh</td><td width="2%">:</td><td><?= e($printed_by) ?></td>
            <td width="15%">Tanggal Cetak</td><td width="2%">:</td><td><?= $printed_date ?></td>
        </tr>
        <tr>
            <td>Periode Laporan</td><td>:</td><td colspan="4"><?= $periode_label ?></td>
        </tr>
    </table>
</div>

<table class="main-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="12%">Waktu</th>
            <th width="10%">NIS</th>
            <th width="15%">Kategori</th>
            <th width="15%">Lokasi</th>
            <th width="22%">Deskripsi Kerusakan</th>
            <th width="12%">Urgensi</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($aspirasis)): ?>
        <tr><td colspan="8" style="text-align:center;padding:20px;color:#888;">Tidak ada data ditemukan.</td></tr>
        <?php else: ?>
        <?php foreach ($aspirasis as $i => $item):
            $status = $item['status'] ?? 'Menunggu';
            $statusClass = match(strtolower($status)) {
                'menunggu' => 'status-menunggu',
                'proses'   => 'status-proses',
                'selesai'  => 'status-selesai',
                default    => '',
            };
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
            <td><?= e($item['nis']) ?></td>
            <td><?= e($item['ket_kategori'] ?? '-') ?></td>
            <td><?= e($item['lokasi']) ?></td>
            <td><?= e($item['ket']) ?></td>
            <td><?= e($item['urgensi'] ?? 'Standar') ?></td>
            <td><span class="status <?= $statusClass ?>"><?= e($status) ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    <p>Bandung, <?= $printed_date ?></p>
    <div class="signature">
        <p>Administrator Sarana &amp; Prasarana</p>
        <div class="signature-line">
            <strong><?= e($printed_by) ?></strong><br>
            <span>NIP. ...........................</span>
        </div>
    </div>
</div>
</body>
</html>
    <?php
    exit;
});
