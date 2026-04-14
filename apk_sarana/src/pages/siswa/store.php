<?php
route('POST', '/siswa', function () {
    require_siswa();
    $siswa = auth_siswa();


    $key = 'submit_aspirasi_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (!rate_limit($key, 10, 60)) {
        http_response_code(429);
        session_flash('error', 'Terlalu banyak permintaan. Coba lagi dalam beberapa menit.');
        redirect('/');
    }

    csrf_abort();

    $data = [
        'nis' => $siswa['nis'],
        'kelas' => $siswa['kelas'],
        'id_kategori' => post('id_kategori'),
        'lokasi' => post('lokasi'),
        'ket' => post('ket'),
        'urgensi' => post('urgensi'),
    ];

    $rules = [
        'nis' => 'required|string|max:10',
        'kelas' => 'required|string|max:10',
        'id_kategori' => 'required|exists:kategoris,id_kategori',
        'lokasi' => 'required|string|max:50',
        'ket' => 'required|string|max:500',
        'urgensi' => 'required|in:Mendesak,Standar,Rendah',
    ];


    $lampiran_errors = [];
    if (!empty($_FILES['lampiran']['name'])) {
        $file = $_FILES['lampiran'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $lampiran_errors[] = 'Gagal upload foto.';
        } elseif ($file['size'] > $maxSize) {
            $lampiran_errors[] = 'Ukuran foto maksimal 5MB.';
        } elseif (!in_array($mimeType, $allowed)) {
            $lampiran_errors[] = 'Format foto harus JPEG, PNG, GIF, atau WEBP.';
        }
    }

    $errors = validate($data, $rules);
    if (!empty($lampiran_errors)) {
        $errors['lampiran'] = $lampiran_errors;
    }

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect('/');
    }

    $pdo = db();


    $stmt = $pdo->prepare("SELECT nis FROM siswas WHERE nis = ?");
    $stmt->execute([$data['nis']]);
    $existing = $stmt->fetch();

    if ($existing) {
        $pdo->prepare("UPDATE siswas SET kelas = ?, updated_at = NOW() WHERE nis = ?")->execute([$data['kelas'], $data['nis']]);
    } else {
        $pdo->prepare("INSERT INTO siswas (nis, kelas, created_at, updated_at) VALUES (?, ?, NOW(), NOW())")->execute([$data['nis'], $data['kelas']]);
    }


    $lampiranPath = null;
    if (!empty($_FILES['lampiran']['name']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK && empty($errors['lampiran'])) {
        $lampiranPath = compress_and_store_image($_FILES['lampiran']);
    }


    $stmt = $pdo->prepare("INSERT INTO input_aspirasis (nis, id_kategori, lokasi, lampiran, ket, urgensi, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$data['nis'], $data['id_kategori'], $data['lokasi'], $lampiranPath, $data['ket'], $data['urgensi']]);
    $id_pelaporan = $pdo->lastInsertId();


    $pdo->prepare("INSERT INTO aspirasis (id_pelaporan, status, id_kategori, created_at, updated_at) VALUES (?, 'Menunggu', ?, NOW(), NOW())")->execute([$id_pelaporan, $data['id_kategori']]);

    clear_old();
    session_flash('success', 'Aspirasi berhasil dikirim!');
    session_set('nis', $data['nis']);
    redirect('/history?nis=' . urlencode($data['nis']));
});
