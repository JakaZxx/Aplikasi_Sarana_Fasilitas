# Dokumentasi Sistem Pengaduan Sarana & Prasarana

Aplikasi ini dibuat oleh Jalu Zakaria Anwar untuk membantu pengelolaan data pengaduan fasilitas sekolah. Tampilannya modern menggunakan Tailwind CSS dan sistemnya ringan menggunakan PHP Native.

## Fitur Utama
Sistem ini punya dua sisi:
1. **Sisi Siswa**: Bisa lapor kerusakan, pilih kategori, isi lokasi, upload foto bukti, dan cek status laporannya udah sampe mana cuma pake NIS.
2. **Sisi Admin**: Dasbor buat pantau semua laporan, bisa update status (Menunggu/Proses/Selesai), kasih feedback, kelola kategori, data siswa, sampe cetak laporan PDF buat arsip.

## Struktur Database
Kita pake database `db_sarana`. Ini gambaran singkat tabel-tabelnya:

- **users**: Data login buat admin.
- **siswas**: Data siswa (NIS, Nama, Kelas).
- **kategoris**: Jenis-jenis laporan (Kelistrikan, Meja & Kursi, dll).
- **input_aspirasis**: Data laporan yang masuk dari siswa.
- **aspirasis**: Data tindak lanjut atau status dari laporan tadi.

## Penjelasan Singkat Kode
Semua logika aplikasi ada di folder `src`. Kita pake sistem routing di `index.php` biar URL-nya rapi.

- `src/helpers/functions.php`: Isinya fungsi-fungsi bantuan kayak buat koneksi database, upload gambar, paginasi, sampe urusan keamanan (CSRF & sanitasi).
- `src/pages/`: Isinya semua halaman website, dipisah antara folder `admin`, `siswa`, dan `auth`.
- `config.php`: Tempat setting koneksi database.

## Kelebihan Sistem
- **Ringan**: Karena pake PHP Native, jalan di server mana aja pasti kenceng.
- **User-friendly**: Tampilan bersih dan responsif, enak dilihat di HP atau Laptop.
- **Aman**: Udah ada proteksi XSS, CSRF, dan validasi input yang ketat.
- **Paginasi & Filter**: Admin gampang nyari data laporan walau datanya udah ribuan.

---
*Dibuat oleh Jalu Zakaria Anwar*
