<?php
require_once '../config.php';

// Check if user is logged in and is Kasir
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Kasir') {
    header('Location: ../login.php');
    exit();
}

// Get unpaid consultations (not in payments table yet)
$query = "SELECT c.*, p.full_name, p.status, p.phone 
          FROM consultations c 
          JOIN patients p ON c.patient_id = p.patient_id 
          LEFT JOIN payments pay ON c.consultation_id = pay.consultation_id 
          WHERE pay.payment_id IS NULL
          ORDER BY c.created_at DESC";
$unpaid_result = mysqli_query($conn, $query);

// Get paid consultations
$paid_query = "SELECT c.*, p.full_name, p.status, pay.amount, pay.payment_status, pay.payment_date 
               FROM consultations c 
               JOIN patients p ON c.patient_id = p.patient_id 
               JOIN payments pay ON c.consultation_id = pay.consultation_id 
               ORDER BY pay.payment_date DESC";
$paid_result = mysqli_query($conn, $paid_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Proses Pembayaran</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pembayaran.php">Proses Pembayaran</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <h2>Konsultasi Belum Dibayar</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Konsultasi</th>
                    <th>Tanggal</th>
                    <th>Nama Pasien</th>
                    <th>Status</th>
                    <th>Diagnosis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($unpaid_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($unpaid_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['consultation_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['consultation_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><strong><?php echo $row['status']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                            <td>
                                <a href="payment_process.php?consultation_id=<?php echo $row['consultation_id']; ?>" class="btn btn-success">Proses Bayar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Semua konsultasi sudah dibayar</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <h2 style="margin-top: 40px;">Riwayat Pembayaran</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Konsultasi</th>
                    <th>Tanggal Bayar</th>
                    <th>Nama Pasien</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Status Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($paid_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($paid_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['consultation_id']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['payment_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>Rp <?php echo number_format($row['amount'], 0, ',', '.'); ?></td>
                            <td>
                                <span style="color: <?php echo $row['payment_status'] == 'Gratis' ? 'green' : 'blue'; ?>">
                                    <strong><?php echo $row['payment_status']; ?></strong>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada pembayaran</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
