# Manual Book: Sistem Pengaduan Sarana dan Prasarana

Selamat datang di Sistem Pengaduan Sarana dan Prasarana. Dokumen ini bakal ngejelasin gimana cara pake sistem ini, baik buat Siswa (yang lapor) maupun Admin (yang ngurus laporan).

---

## 1. Pendahuluan
Sistem ini dibikin biar urusan lapor-melapor fasilitas sekolah yang rusak jadi lebih rapi dan tercatat. Semua laporan bakal masuk ke database dan bisa dipantau progresnya.

## 2. Panduan Penggunaan buat Siswa

### A. Cara Kirim Laporan
1. Buka halaman utama aplikasi.
2. Login pake **NIS** (Nomor Induk Siswa) dan password kamu. Kalau belum tahu, tanya ke admin ya.
3. Di halaman depan, kamu bakal liat form **"Kirim Aduan"**.
4. Isi data-datanya:
   - **Kategori**: Pilih jenis fasilitas yang rusak (misal: Kelistrikan, Meja & Kursi).
      - **Lokasi**: Tulis di mana kerusakannya (misal: Ruang Kelas XII RPL 3).
         - **Deskripsi**: Ceritain kerusakannya gimana.
            - **Urgensi**: Pilih seberapa darurat kerusakannya (Mendesak/Standar/Rendah).
               - **Foto Bukti**: Upload foto kerusakannya biar admin jelas liatnya.
               5. Klik tombol **"Kirim Laporan"**.

               ### B. Cara Cek Status Laporan
               1. Klik menu **"Riwayat"** atau **"Status Pengaduan"**.
               2. Masukin **NIS** kamu di kolom pencarian.
               3. Kamu bakal liat daftar laporan yang pernah kamu kirim beserta statusnya:
                  - **Menunggu**: Laporan baru masuk, belum diapa-apain.
                     - **Proses**: Lagi dikerjain sama teknisi/pak sarpras.
                        - **Selesai**: Kerusakan udah dibetulin.

                        ---

                        ## 3. Panduan Penggunaan buat Admin

                        ### A. Cara Login Admin
                        1. Klik link **"Masuk sebagai Admin"** di bawah form login siswa, atau buka URL `/login`.
                        2. Masukin **Username** dan **Password** admin.

                        ### B. Mengelola Laporan (Dashboard)
                        1. Setelah login, kamu langsung masuk ke **Dashboard**.
                        2. Di sini ada daftar semua laporan dari siswa. Kamu bisa:
                           - **Filter**: Cari laporan berdasarkan Bulan, Kategori, atau Tingkat Urgensi.
                              - **Aksi (Update Status)**: Klik tombol pensil atau "Update" buat ganti status laporan. Kamu bisa isi **Feedback** (catatan) buat siswanya, misal: "Kursi lagi pesen baru ya".
                                 - **Lihat Foto**: Klik gambar buat liat foto bukti yang dikirim siswa.

                                 ### C. Mengelola Pengguna & Kategori
                                 1. **Menu Kategori**: Buat nambahin atau edit jenis fasilitas sekolah (misal: tambah kategori "Alat Olahraga").
                                 2. **Menu Siswa**: Buat liat atau nambah data siswa yang boleh lapor.
                                 3. **Menu Admin**: Buat nambahin akun admin lain kalau butuh bantuan tim sarpras tambahan.

                                 ### D. Cetak Laporan (PDF)
                                 1. Di Dashboard, klik tombol **"Cetak PDF"**.
                                 2. Pilih filter yang dimau (misal: laporan bulan ini aja).
                                 3. Klik tombol print/download buat simpen dokumen PDF-nya buat laporan ke atasan.

                                 ---

                                 ## 4. Tips & Troubleshooting
                                 - **Foto Gagal Upload**: Pastiin ukuran foto nggak terlalu gede dan formatnya (.jpg, .png, atau .webp).
                                 - **Lupa Password**: Kalau admin lupa password, minta admin lain buat gantiin di menu "User Admin". Kalau siswa, bisa dibantu di menu "Siswa".
                                 - **Data Nggak Muncul**: Cek koneksi internet atau pastiin database sudah terhubung di file `config.php`.

---
*Dibuat oleh Jalu Zakaria Anwar*
