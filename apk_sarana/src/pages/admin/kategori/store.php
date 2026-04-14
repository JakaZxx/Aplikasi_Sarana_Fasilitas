<?php
route('POST', '/admin/kategori', function () {
    require_auth();
    csrf_abort();

    $data  = ['ket_kategori' => post('ket_kategori')];
    $rules = ['ket_kategori' => 'required|string|max:50|unique:kategoris,ket_kategori'];
    $errors = validate($data, $rules);

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect('/admin/kategori/create');
    }

    db()->prepare("INSERT INTO kategoris (ket_kategori, created_at, updated_at) VALUES (?, NOW(), NOW())")
       ->execute([$data['ket_kategori']]);

    clear_old();
    session_flash('success', 'Kategori berhasil ditambahkan.');
    redirect('/admin/kategori');
});
