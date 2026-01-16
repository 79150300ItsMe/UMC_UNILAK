<?php
require_once '../config.php';

// Check if user is logged in and is Dokter
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Dokter') {
    header('Location: ../login.php');
    exit();
}

// Get all consultations
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT c.*, p.full_name, p.status 
          FROM consultations c 
          JOIN patients p ON c.patient_id = p.patient_id 
          WHERE 1=1";
if ($search) {
    $query .= " AND (c.consultation_id LIKE '%$search%' OR p.full_name LIKE '%$search%' OR c.patient_id LIKE '%$search%')";
}
$query .= " ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Konsultasi - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Data Konsultasi Medis</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="daftar_konsultasi.php">Data Konsultasi</a>
            <a href="tambah_konsultasi.php">Tambah Konsultasi</a>
        </div>
        
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Cari ID Konsultasi, Nama Pasien..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Cari</button>
                <?php if ($search): ?>
                    <a href="daftar_konsultasi.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID Konsultasi</th>
                    <th>Tanggal</th>
                    <th>ID Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Diagnosis</th>
                    <th>Obat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Convert DDMMYY to DD/MM/YYYY
                        $date_str = $row['consultation_date'];
                        if (strlen($date_str) == 6) {
                            $day = substr($date_str, 0, 2);
                            $month = substr($date_str, 2, 2);
                            $year = '20' . substr($date_str, 4, 2);
                            $formatted_date = $day . '/' . $month . '/' . $year;
                        } else {
                            $formatted_date = $date_str;
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['consultation_id']); ?></td>
                            <td><?php echo $formatted_date; ?></td>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['complaint']); ?></td>
                            <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                            <td><?php echo htmlspecialchars($row['medication']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data konsultasi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
