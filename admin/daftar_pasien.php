<?php
require_once '../config.php';

// Check if user is logged in and is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('Location: ../login.php');
    exit();
}

// Get all patients
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT * FROM patients WHERE 1=1";
if ($search) {
    $query .= " AND (patient_id LIKE '%$search%' OR full_name LIKE '%$search%' OR phone LIKE '%$search%')";
}
$query .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Data Pasien</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="tambah_pasien.php">Tambah Pasien Baru</a>
            <a href="../reports/laporan_transaksi.php">Laporan Transaksi</a>
        </div>
        
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Cari ID, Nama, atau Telepon..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Cari</button>
                <?php if ($search): ?>
                    <a href="daftar_pasien.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID Pasien</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Lahir</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nik']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['birth_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><strong><?php echo $row['status']; ?></strong></td>
                            <td class="action-links">
                                <a href="ubah_pasien.php?id=<?php echo $row['patient_id']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data pasien</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
