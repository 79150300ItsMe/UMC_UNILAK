<?php
require_once '../config.php';

// Check if user is logged in and is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Auto-generate Patient ID
    $query = "SELECT patient_id FROM patients ORDER BY patient_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['patient_id'];
        $number = intval(substr($last_id, 1)) + 1;
        $patient_id = 'P' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        $patient_id = 'P001';
    }
    
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "INSERT INTO patients (patient_id, nik, birth_date, full_name, address, phone, status) 
              VALUES ('$patient_id', '$nik', '$birth_date', '$full_name', '$address', '$phone', '$status')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Pasien berhasil ditambahkan dengan ID: ' . $patient_id;
    } else {
        $error = 'Gagal menambahkan pasien: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pasien Baru - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Tambah Pasien Baru</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="tambah_pasien.php">Tambah Pasien Baru</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="daftar_pasien.php">Lihat Data Pasien</a>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="alert alert-info">
                <strong>Info:</strong> ID Pasien akan di-generate otomatis oleh sistem
            </div>
            
            <div class="form-group">
                <label>NIK (16 digit) *</label>
                <input type="text" name="nik" required placeholder="16 digit NIK" maxlength="16" pattern="[0-9]{16}">
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label>Tanggal Lahir *</label>
                <input type="date" name="birth_date" required>
            </div>
            
            <div class="form-group">
                <label>Alamat Tempat Tinggal *</label>
                <textarea name="address" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Nomor HP *</label>
                <input type="text" name="phone" required placeholder="08xxxxxxxxxx">
            </div>
            
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Dosen">Dosen</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Simpan Pasien</button>
            <a href="daftar_pasien.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
