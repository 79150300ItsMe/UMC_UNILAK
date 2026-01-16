# Sistem Informasi Klinik Unilak Medical Center

Sistem informasi berbasis web untuk mengelola data pasien, konsultasi medis, pembayaran, dan laporan transaksi di klinik Unilak Medical Center.

## Fitur Utama

### 1. Login System

- **Admin**: Mengelola data pasien
- **Dokter**: Mencatat konsultasi medis
- **Kasir**: Memproses pembayaran

### 2. Modul Admin

- Melihat dan memverifikasi data pasien
- Mendaftarkan pasien baru (ID, Tanggal Lahir, Nama, Alamat, Telepon, Status)
- Mengedit data pasien
- Akses laporan transaksi

### 3. Modul Dokter

- Melihat riwayat konsultasi
- Menginput konsultasi baru (ID Konsultasi, Tanggal DDMMYY, ID Pasien, NIK, Keluhan, Diagnosis, Obat)

### 4. Modul Kasir

- Memproses pembayaran dengan logika:
  - **Dosen/Karyawan**: GRATIS (Rp 0)
  - **Mahasiswa/Umum**: BERBAYAR
- Melihat riwayat pembayaran

### 5. Laporan Transaksi

- Filter laporan per bulan
- Total transaksi dan pendapatan
- Fitur cetak laporan

## Instalasi

### Persyaratan

- XAMPP (Apache + MySQL + PHP)
- Web browser

### Langkah Instalasi

1. **Pastikan XAMPP sudah terinstall dan berjalan**

   - Jalankan Apache
   - Jalankan MySQL

2. **File sudah berada di folder yang benar**

   ```
   c:\xampp\htdocs\UMC_UNILAK\
   ```

3. **Import Database**

   - Buka browser dan akses: `http://localhost/phpmyadmin/`
   - Klik tab "SQL"
   - Copy seluruh isi file `database/umc_clinic.sql`
   - Paste di SQL editor dan klik "Go"
   - Database `umc_clinic` akan otomatis terbuat dengan data sample

4. **Akses Aplikasi**
   - Buka browser
   - Akses: `http://localhost/UMC_UNILAK/`
   - Anda akan diarahkan ke halaman login

## Data Login Testing

```
Admin:
Username: admin
Password: admin123

Kasir:
Username: kasir
Password: kasir123

Dokter:
Username: dokter
Password: dokter123
```

## Struktur Database

### Table: users

- Login credentials untuk Admin, Kasir, Dokter
- Username: 3-10 karakter
- Password: 3-10 karakter (MD5 hashed)

### Table: patients

- Data pasien (ID, Nama, Tanggal Lahir, Alamat, Telepon, Status)
- Status: Dosen, Karyawan, Mahasiswa, Umum

### Table: consultations

- Data konsultasi medis
- Tanggal format DDMMYY
- Keluhan, Diagnosis, Obat

### Table: payments

- Data pembayaran
- Status pembayaran (Gratis/Lunas)
- Dosen/Karyawan = Gratis
- Mahasiswa/Umum = Berbayar

## Alur Kerja Sistem

1. **Pendaftaran Pasien** (Admin)

   - Admin mendaftarkan pasien baru dengan data lengkap
   - Status pasien menentukan biaya layanan

2. **Konsultasi Medis** (Dokter)

   - Dokter menginput data konsultasi untuk pasien yang sudah terdaftar
   - Data tersimpan untuk proses pembayaran

3. **Pembayaran** (Kasir)

   - Kasir memproses pembayaran berdasarkan konsultasi
   - Sistem otomatis menentukan gratis/berbayar berdasarkan status pasien

4. **Laporan** (Admin/Kasir)
   - Akses laporan transaksi bulanan
   - Cetak laporan untuk dokumentasi

## Struktur File

```
UMC_UNILAK/
├── admin/
│   ├── dashboard.php
│   ├── patients.php
│   ├── patient_add.php
│   └── patient_edit.php
├── dokter/
│   ├── dashboard.php
│   ├── consultations.php
│   └── consultation_add.php
├── kasir/
│   ├── dashboard.php
│   ├── payments.php
│   └── payment_process.php
├── reports/
│   └── transaction_report.php
├── database/
│   └── umc_clinic.sql
├── config.php
├── login.php
├── logout.php
├── index.php
└── style.css
```

## Catatan Penting

- Tampilan dibuat **sesimple mungkin** dengan fokus pada **fungsi dan alur kerja**
- Sistem mengikuti use-case diagram dan ketentuan yang diberikan
- Status pasien **menentukan biaya layanan** secara otomatis
- Format tanggal konsultasi menggunakan **DDMMYY** sesuai spesifikasi
- Laporan dapat di-**print** untuk dokumentasi

## Troubleshooting

**Database connection error:**

- Pastikan MySQL berjalan di XAMPP
- Cek file `config.php` untuk kredensial database

**Login gagal:**

- Pastikan database sudah diimport
- Gunakan username/password yang benar
- Username dan password harus 3-10 karakter

**Halaman blank:**

- Cek error di XAMPP control panel
- Pastikan PHP error reporting aktif

## Dibuat Oleh

Sistem ini dibuat untuk memenuhi tugas Ujian Laboratorium:

- **Mata Kuliah**: Pemrograman
- **Program Studi**: Teknik Informatika & Sistem Informasi
- **Institusi**: Universitas Lancang Kuning, Fakultas Ilmu Komputer
