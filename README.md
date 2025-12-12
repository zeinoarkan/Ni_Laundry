Ni Laundry Web System
1. Overview

Ni Laundry Web System adalah aplikasi berbasis web yang dirancang untuk melakukan digitalisasi proses operasional Ni Laundry, mencakup manajemen pelanggan, layanan, transaksi, status cucian, dan laporan keuangan. Sistem ini menggantikan proses manual yang rawan kesalahan dan meningkatkan efisiensi melalui pencatatan terstruktur, pemantauan status real time, serta notifikasi otomatis kepada pelanggan. Pengembangan dilakukan menggunakan Laravel dan MySQL dengan pendekatan Agile.

2. Key Features
2.1 Admin/Pegawai

Login dan autentikasi.

Manajemen layanan (tambah, ubah, hapus).

Manajemen pelanggan dan data profil.

Pembuatan dan pemantauan pesanan.

Manajemen status cucian (Menunggu, Diproses, Selesai).

Validasi dan pencatatan pembayaran.

Laporan dan rekap transaksi harian/mingguan/bulanan.

2.2 Pelanggan

Login dan pengelolaan akun.

Melihat daftar layanan dan detail harga.

Pemesanan layanan serta input berat cucian.

Pemantauan status cucian secara real time.

Pemilihan metode pembayaran.

Notifikasi otomatis melalui WhatsApp.

3. System Description

Sistem bekerja sebagai platform terintegrasi yang menghubungkan pelanggan dan admin/pegawai. Seluruh data layanan, pelanggan, dan transaksi dikelola dalam basis data terpusat. Aplikasi menerapkan standar keamanan berbasis HTTPS, enkripsi password, serta validasi input untuk memastikan integritas data. Frontend menggunakan Bootstrap untuk memastikan tampilan responsif di berbagai perangkat.

4. User Categories
Admin/Pegawai

Aktor utama yang mengelola seluruh proses operasional. Memiliki akses penuh terhadap modul layanan, pelanggan, pesanan, status, dan laporan.

Pelanggan

Aktor yang melakukan pemesanan layanan, melihat status cucian, memilih pembayaran, dan menerima notifikasi.

5. System Constraints

Framework: Laravel (PHP).

Database: MySQL.

Minimal browser: Chrome, Firefox, Edge versi terbaru.

Sistem hanya berjalan optimal pada koneksi internet stabil.

Testimoni pelanggan diambil dari Google Maps (read-only).

Seluruh lisensi perangkat lunak dan server disediakan oleh pemilik usaha.

6. UI/UX Specification
Responsiveness

Mendukung perangkat mobile, tablet, dan desktop.

Minimal resolusi layar 360 × 640 px.

Interface Standards

Palet warna utama: biru muda dan putih.

Tombol utama menggunakan warna biru tua.

Kode warna tombol aksi: Hijau (Simpan), Kuning (Edit), Merah (Hapus).

Penggunaan breadcrumb pada setiap halaman untuk navigasi.

Feedback and Notifications

Pesan kesalahan ditampilkan dengan warna merah.

Pesan berhasil ditampilkan dengan warna hijau.

Notifikasi status dan promosi menggunakan popup alert.

7. Hardware Requirements
Admin/Pegawai

Prosesor setara Intel i3 Gen 7 atau lebih tinggi.

RAM minimal 4 GB (disarankan 8 GB).

Penyimpanan minimal 128 GB SSD.

Browser versi terbaru.

Pelanggan

Perangkat smartphone atau laptop.

RAM minimal 2 GB.

Browser versi terbaru.

8. Software and Communication Requirements

Protokol komunikasi menggunakan HTTPS dengan TLS/SSL.

Enkripsi password menggunakan bcrypt.

Pertukaran data antar komponen menggunakan JSON.

Integrasi WhatsApp API untuk notifikasi otomatis.

9. Functional Requirements
Admin

Manajemen layanan.

Manajemen pelanggan.

Manajemen pesanan.

Pengaturan dan pembaruan status cucian.

Validasi pembayaran.

Pembuatan dan ekspor laporan transaksi.

Pelanggan

Login dan pengelolaan akun.

Pemilihan layanan dan input berat cucian.

Pemantauan status cucian.

Pemilihan metode pembayaran.

Riwayat transaksi.

Penerimaan notifikasi otomatis.

10. Development Methodology

Pengembangan dilakukan dengan metode Scrum yang terdiri dari:

Perencanaan sprint.

Implementasi fitur dalam iterasi singkat.

Pengujian berkala.

Review dan evaluasi bersama pemilik usaha.
