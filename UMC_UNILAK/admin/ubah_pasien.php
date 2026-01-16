<?php
require_once '../config.php';

// Check if user is logged in and is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

// Get patient ID from URL
$patient_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (!$patient_id) {
    header('Location: daftar_pasien.php');
    exit();
}

// Get patient data
$query = "SELECT * FROM patients WHERE patient_id = '$patient_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: daftar_pasien.php');
    exit();
}

$patient = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE patients SET 
              birth_date = '$birth_date',
              nik = '$nik',
              full_name = '$full_name',
              address = '$address',
              phone = '$phone',
              status = '$status'
              WHERE patient_id = '$patient_id'";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data pasien berhasil diupdate!';
        // Refresh patient data
        $query = "SELECT * FROM patients WHERE patient_id = '$patient_id'";
        $result = mysqli_query($conn, $query);
        $patient = mysqli_fetch_assoc($result);
    } else {
        $error = 'Gagal mengupdate data: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pasien - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Edit Data Pasien</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="tambah_pasien.php">Tambah Pasien Baru</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>ID Pasien</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['patient_id']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label>NIK (16 digit) *</label>
                <input type="text" name="nik" value="<?php echo htmlspecialchars($patient['nik']); ?>" required maxlength="16" pattern="[0-9]{16}">
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Tanggal Lahir *</label>
                <input type="date" name="birth_date" value="<?php echo $patient['birth_date']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Alamat Tempat Tinggal *</label>
                <textarea name="address" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Nomor HP *</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="Dosen" <?php echo $patient['status'] == 'Dosen' ? 'selected' : ''; ?>>Dosen</option>
                    <option value="Karyawan" <?php echo $patient['status'] == 'Karyawan' ? 'selected' : ''; ?>>Karyawan</option>
                    <option value="Mahasiswa" <?php echo $patient['status'] == 'Mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                    <option value="Umum" <?php echo $patient['status'] == 'Umum' ? 'selected' : ''; ?>>Umum</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Update Data</button>
            <a href="daftar_pasien.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
