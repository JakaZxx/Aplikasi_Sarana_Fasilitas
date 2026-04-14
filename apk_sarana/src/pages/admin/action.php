<?php
route('PUT', '/admin/action/{id}', function (string $id_aspirasi) {
    require_auth();
    csrf_abort();

    $stmt = db()->prepare("SELECT * FROM aspirasis WHERE id_aspirasi = ?");
    $stmt->execute([$id_aspirasi]);
    $aspirasi = $stmt->fetch();

    if (!$aspirasi) {
        http_response_code(404);
        die('Aspirasi not found.');
    }

    $data = [
        'status'   => post('status'),
        'feedback' => post('feedback'),
    ];

    $rules = ['status' => 'required|in:Menunggu,Proses,Selesai'];
    $errors = validate($data, $rules);

    if (empty($errors)) {
        db()->prepare("UPDATE aspirasis SET status = ?, feedback = ?, updated_at = NOW() WHERE id_aspirasi = ?")
           ->execute([$data['status'], $data['feedback'] ?: null, $id_aspirasi]);
        session_flash('success', 'Status aspirasi berhasil diupdate.');
    }

    redirect('/admin/dashboard?' . http_build_query(array_filter([
        'tanggal'  => get_param('tanggal'),
        'bulan'    => get_param('bulan'),
        'nis'      => get_param('nis'),
        'kategori' => get_param('kategori'),
        'urgensi'  => get_param('urgensi'),
    ], fn($v) => $v !== '')));
});
