<?php
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}

// Get filter parameters
$month = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : date('Y-m');
$start_date = $month . '-01';
$end_date = date('Y-m-t', strtotime($start_date));

// Get all payments for the selected month
$query = "SELECT p.*, c.consultation_date, c.diagnosis, c.medication, 
          pat.full_name, pat.status, u.full_name as cashier_name
          FROM payments p
          JOIN consultations c ON p.consultation_id = c.consultation_id
          JOIN patients pat ON p.patient_id = pat.patient_id
          JOIN users u ON p.cashier_id = u.user_id
          WHERE p.payment_date BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
          ORDER BY p.payment_date DESC";
$result = mysqli_query($conn, $query);

// Calculate totals
$total_transactions = mysqli_num_rows($result);
$total_amount = 0;
$total_free = 0;
$total_paid = 0;

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
    $total_amount += $row['amount'];
    if ($row['payment_status'] == 'Gratis') {
        $total_free++;
    } else {
        $total_paid++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info no-print">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Laporan Transaksi Bulanan</h1>
        <h3>Unilak Medical Center</h3>
        
        <div class="navigation no-print">
            <?php if ($_SESSION['role'] == 'Admin'): ?>
                <a href="../admin/dasbor.php">Dashboard</a>
                <a href="../admin/daftar_pasien.php">Data Pasien</a>
            <?php elseif ($_SESSION['role'] == 'Kasir'): ?>
                <a href="../kasir/dasbor.php">Dashboard</a>
                <a href="../kasir/daftar_pembayaran.php">Pembayaran</a>
            <?php endif; ?>
            <a href="laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <div class="no-print" style="margin: 20px 0;">
            <form method="GET" action="" style="display: flex; align-items: center; gap: 10px;">
                <label><strong>Pilih Bulan:</strong></label>
                <input type="month" name="month" value="<?php echo $month; ?>" required>
                <button type="submit" class="btn">Filter</button>
                <button type="button" onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
            </form>
        </div>
        
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <strong>Periode:</strong> <?php echo date('F Y', strtotime($start_date)); ?><br>
            <strong>Total Transaksi:</strong> <?php echo $total_transactions; ?> transaksi<br>
            <strong>Transaksi Gratis:</strong> <?php echo $total_free; ?> transaksi<br>
            <strong>Transaksi Berbayar:</strong> <?php echo $total_paid; ?> transaksi<br>
            <strong>Total Pendapatan:</strong> Rp <?php echo number_format($total_amount, 0, ',', '.'); ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>ID Konsultasi</th>
                    <th>Nama Pasien</th>
                    <th>Status</th>
                    <th>Diagnosis</th>
                    <th>Jumlah</th>
                    <th>Status Bayar</th>
                    <th class="no-print">Kasir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['payment_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['consultation_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                            <td>Rp <?php echo number_format($row['amount'], 0, ',', '.'); ?></td>
                            <td>
                                <strong style="color: <?php echo $row['payment_status'] == 'Gratis' ? 'green' : 'blue'; ?>">
                                    <?php echo $row['payment_status']; ?>
                                </strong>
                            </td>
                            <td class="no-print"><?php echo htmlspecialchars($row['cashier_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background: #f8f9fa; font-weight: bold;">
                        <td colspan="6" style="text-align: right;">TOTAL</td>
                        <td>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></td>
                        <td colspan="2"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak ada transaksi pada bulan ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 40px; text-align: right;" class="no-print">
            <p><em>Laporan ini dihasilkan secara otomatis oleh sistem</em></p>
        </div>
        
        <div style="margin-top: 60px; display: none;" class="print-only">
            <div style="text-align: right;">
                <p>Pekanbaru, <?php echo date('d F Y'); ?></p>
                <br><br><br>
                <p>_______________________</p>
                <p>Penanggung Jawab</p>
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            .print-only {
                display: block !important;
            }
        }
    </style>
</body>
</html>
