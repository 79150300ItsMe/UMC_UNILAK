# Unilak Medical Center - Sistem Informasi Klinik

Sistem informasi untuk mengelola data pasien, konsultasi medis, dan pembayaran di klinik Universitas Lancang Kuning.

## 🏥 Fitur Utama

### Admin

- Mengelola data pasien (tambah, edit, lihat)
- Melihat status pemeriksaan pasien
- Akses laporan transaksi

### Dokter

- Melihat daftar pasien
- Mencatat hasil konsultasi medis
- Auto-fill data pasien berdasarkan ID

### Kasir

- Memproses pembayaran konsultasi
- Sistem tarif otomatis (Gratis untuk Dosen/Karyawan)
- Riwayat pembayaran

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL/MariaDB
- Apache Web Server (XAMPP/WAMP)

## 🚀 Instalasi

1. **Clone repository**

   ```bash
   git clone https://github.com/79150300ItsMe/UMC_UNILAK.git
   ```

2. **Pindahkan ke folder htdocs**

   ```
   C:\xampp\htdocs\UMC_UNILAK\
   ```

3. **Import database**

   - Buka phpMyAdmin: `http://localhost/phpmyadmin/`
   - Import file: `database/umc_clinic.sql`

4. **Akses sistem**
   ```
   http://localhost/UMC_UNILAK/
   ```

## 🔐 Login Credentials

| Role   | ID         | Password  |
| ------ | ---------- | --------- |
| Admin  | 1234567890 | admin123  |
| Kasir  | 1234567891 | kasir123  |
| Dokter | 1234567892 | dokter123 |

## 📁 Struktur File

```
UMC_UNILAK/
├── admin/              # Modul Admin
│   ├── dasbor.php
│   ├── daftar_pasien.php
│   ├── tambah_pasien.php
│   └── ubah_pasien.php
├── dokter/             # Modul Dokter
│   ├── dasbor.php
│   ├── daftar_pasien.php
│   ├── daftar_konsultasi.php
│   └── tambah_konsultasi.php
├── kasir/              # Modul Kasir
│   ├── dasbor.php
│   ├── daftar_pembayaran.php
│   └── proses_pembayaran.php
├── reports/            # Modul Laporan
│   └── laporan_transaksi.php
├── database/           # Database Schema
│   └── umc_clinic.sql
├── config.php          # Konfigurasi Database
├── login.php           # Halaman Login
├── logout.php          # Logout Handler
├── style.css           # Stylesheet
└── index.php           # Entry Point

```

## 🎯 Fitur Khusus

- **Auto-generate ID**: ID Pasien dan ID Konsultasi otomatis
- **Validasi ID**: Login menggunakan ID 10 digit angka
- **Smart Pricing**: Tarif otomatis berdasarkan status (Dosen/Karyawan gratis)
- **Date Auto-fill**: Tanggal konsultasi otomatis dari tanggal pendaftaran
- **Status Tracking**: Tracking status pemeriksaan pasien

## 🛠️ Teknologi

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript

## 📝 Ketentuan Sistem

Sistem ini dibuat mengikuti spesifikasi:

1. Login dengan ID 10 digit angka (validasi tipe integer)
2. Admin mengelola data pasien (termasuk NIK)
3. Dokter mencatat konsultasi (data pasien auto-fill)
4. Kasir memproses pembayaran (tarif berbasis status)
5. Laporan transaksi bulanan

## 📄 Lisensi

Dibuat untuk keperluan akademik Universitas Lancang Kuning.

## 👨‍💻 Developer

Developed with ❤️ for Unilak Medical Center
