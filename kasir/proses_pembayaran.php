<?php
require_once '../config.php';

// Check if user is logged in and is Kasir
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Kasir') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$consultation_id = isset($_GET['consultation_id']) ? mysqli_real_escape_string($conn, $_GET['consultation_id']) : '';

if (!$consultation_id) {
    header('Location: daftar_pembayaran.php');
    exit();
}

// Get consultation details
$query = "SELECT c.*, p.full_name, p.status, p.phone 
          FROM consultations c 
          JOIN patients p ON c.patient_id = p.patient_id 
          WHERE c.consultation_id = '$consultation_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: daftar_pembayaran.php');
    exit();
}

$consultation = mysqli_fetch_assoc($result);

// Check if already paid
$check_query = "SELECT * FROM payments WHERE consultation_id = '$consultation_id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    header('Location: daftar_pembayaran.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $consultation['patient_id'];
    $patient_status = $consultation['status'];
    $cashier_id = $_SESSION['user_id'];
    
    // Determine amount and payment status based on patient status
    // Dosen/Karyawan = FREE, Mahasiswa/Umum = PAID
    if ($patient_status == 'Dosen' || $patient_status == 'Karyawan') {
        $amount = 0;
        $payment_status = 'Gratis';
    } else {
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $payment_status = 'Lunas';
    }
    
    $query = "INSERT INTO payments (consultation_id, patient_id, patient_status, amount, payment_status, cashier_id) 
              VALUES ('$consultation_id', '$patient_id', '$patient_status', '$amount', '$payment_status', '$cashier_id')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: daftar_pembayaran.php');
        exit();
    } else {
        $error = 'Gagal memproses pembayaran: ' . mysqli_error($conn);
    }
}

// Determine if payment is free
$is_free = ($consultation['status'] == 'Dosen' || $consultation['status'] == 'Karyawan');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran - Unilak Medical Center</title>
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
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div style="padding: 20px; background: #f8f9fa; border-radius: 5px; margin-bottom: 20px;">
            <h3>Detail Konsultasi</h3>
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 200px;"><strong>ID Konsultasi</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['consultation_id']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Tanggal</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['consultation_date']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>ID Pasien</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['patient_id']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Nama Pasien</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['full_name']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Status Pasien</strong></td>
                    <td style="border: none;">: <strong style="color: <?php echo $is_free ? 'green' : 'blue'; ?>"><?php echo $consultation['status']; ?></strong></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Keluhan</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['complaint']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Diagnosis</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['diagnosis']); ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Obat</strong></td>
                    <td style="border: none;">: <?php echo htmlspecialchars($consultation['medication']); ?></td>
                </tr>
            </table>
        </div>
        
        <?php if ($is_free): ?>
            <div class="alert alert-success">
                <strong>Status: GRATIS</strong><br>
                Pasien dengan status <?php echo $consultation['status']; ?> mendapatkan layanan gratis.
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php if (!$is_free): ?>
                <div class="form-group">
                    <label>Jumlah Pembayaran (Rp) *</label>
                    <input type="number" name="amount" required min="1" placeholder="Masukkan jumlah pembayaran">
                </div>
            <?php else: ?>
                <input type="hidden" name="amount" value="0">
            <?php endif; ?>
            
            <button type="submit" class="btn btn-success">
                <?php echo $is_free ? 'Konfirmasi (Gratis)' : 'Proses Pembayaran'; ?>
            </button>
            <a href="daftar_pembayaran.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
