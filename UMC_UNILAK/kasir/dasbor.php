<?php
require_once '../config.php';

// Check if user is logged in and is Kasir
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Kasir') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Dashboard Kasir</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pembayaran.php">Proses Pembayaran</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <div style="margin-top: 30px;">
            <h2>Selamat Datang <?php echo $_SESSION['full_name']; ?></h2>
            <p>Silakan pilih menu di atas untuk memproses pembayaran pasien.</p>
            
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
                <h3>Fungsi Kasir:</h3>
                <ul style="margin-left: 20px; line-height: 1.8;">
                    <li>Memproses pembayaran konsultasi pasien</li>
                    <li>Status Dosen/Karyawan: <strong>GRATIS</strong></li>
                    <li>Status Mahasiswa/Umum: <strong>BERBAYAR</strong></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
