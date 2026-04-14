<?php
route('DELETE', '/admin/kategori/{id}', function (string $id) {
    require_auth();
    csrf_abort();

    $stmt = db()->prepare("SELECT * FROM kategoris WHERE id_kategori = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();

    if (!$kategori) {
        session_flash('error', 'Kategori tidak ditemukan.');
        redirect('/admin/kategori');
    }

    // Check if kategori is in use
    $stmtCheck = db()->prepare("SELECT COUNT(*) FROM input_aspirasis WHERE id_kategori = ?");
    $stmtCheck->execute([$id]);
    $count = (int)$stmtCheck->fetchColumn();

    if ($count > 0) {
        session_flash('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $count . ' data pengaduan.');
        redirect('/admin/kategori');
    }

    db()->prepare("DELETE FROM kategoris WHERE id_kategori = ?")->execute([$id]);
    session_flash('success', 'Kategori berhasil dihapus.');
    redirect('/admin/kategori');
});
