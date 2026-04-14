<?php
route('PUT', '/admin/kategori/{id}', function (string $id) {
    require_auth();
    csrf_abort();

    $stmt = db()->prepare("SELECT * FROM kategoris WHERE id_kategori = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();

    if (!$kategori) {
        session_flash('error', 'Kategori tidak ditemukan.');
        redirect('/admin/kategori');
    }

    $data  = ['ket_kategori' => post('ket_kategori')];
    $rules = ['ket_kategori' => "required|string|max:50|unique:kategoris,ket_kategori,$id,id_kategori"];
    $errors = validate($data, $rules);

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect("/admin/kategori/$id/edit");
    }

    db()->prepare("UPDATE kategoris SET ket_kategori = ?, updated_at = NOW() WHERE id_kategori = ?")
       ->execute([$data['ket_kategori'], $id]);

    clear_old();
    session_flash('success', 'Kategori berhasil diperbarui.');
    redirect('/admin/kategori');
});
