<?php
require_once '../config.php';

// Check if user is logged in and is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Dashboard Admin</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="tambah_pasien.php">Tambah Pasien Baru</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <div style="margin-top: 30px;">
            <h2>Selamat Datang di Sistem Informasi Klinik</h2>
            <p>Silakan pilih menu di atas untuk mengelola data pasien.</p>
            
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
                <h3>Fungsi Admin:</h3>
                <ul style="margin-left: 20px; line-height: 1.8;">
                    <li>Melihat dan memverifikasi data pasien</li>
                    <li>Mendaftarkan pasien baru</li>
                    <li>Mengedit data pasien</li>
                    <li>Melihat laporan transaksi</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
