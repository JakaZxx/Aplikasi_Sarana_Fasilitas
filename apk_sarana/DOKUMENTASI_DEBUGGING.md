# Catatan Perbaikan (Debugging) - Migrasi ke PHP Native

Ini daftar masalah yang sempet muncul pas migrasi dari Laravel ke PHP Native, sekalian cara nanganinnya biar jadi pelajaran buat kedepannya.

## 1. Masalah Background & Tampilan Form NIS
- **Problem**: Pas buka riwayat pengaduan, form NIS-nya jadi kaku dan warnanya berantakan pas ganti-ganti Dark/Light mode. Ternyata gara-gara ada inline style di `<body>`.
- **Solusi**: Copot semua style inline di tag `<body>`, terus pake class CSS dari Tailwind aja (`bg-main-image`). Sekarang tampilannya udah pas dan nggak pecah lagi.

## 2. Link Paginasi "Nyasar" ke Beranda
- **Problem**: Pas lagi di dashboard admin terus klik halaman 2 di tabel, eh malah dilempar ke halaman depan (index).
- **Solusi**: Fungsinya diganti. Tadinya cuma ngambil path pendek, sekarang dibikin pake URL lengkap biar tombolnya tahu persis mau lari ke halaman mana. Udah aman sekarang.

## 3. Foto Laporan Nggak Muncul (Broken)
- **Problem**: Ada foto yang muncul di versi lama (Laravel) tapi nggak ada di versi baru. Ternyata folder penyimpanannya beda tempat.
- **Solusi**: Bikin script `sync_images.php`. Script ini fungsinya nyamain folder foto dari Laravel ke folder `public/lampiran` di versi Native. Tinggal jalanin sekali, semua foto langsung sinkron.

## 4. Bikin CRUD Admin Biar Gampang
- **Problem**: Tadinya kalau mau nambah atau hapus admin harus lewat database langsung (phpMyAdmin), ribet.
- **Solusi**: Dibuatin fitur CRUD lengkap di halaman `admin/users.php`. Sekarang admin bisa nambah, edit, atau hapus user admin lain langsung dari aplikasi pake modal popup yang cakep.

## 5. Error "Crash" di Halaman History (Siswa)
- **Problem**: Kadang halaman history siswa blank putih atau nggak keluar form-nya.
- **Solusi**: Ternyata ada error di fungsi `e()` gara-gara nerima data kosong (null) di PHP 8. Fungsinya udah dibetulin biar bisa nerima data null tanpa bikin website mati. Sekarang lancar jaya.

---
*Dibuat oleh Jalu Zakaria Anwar*
