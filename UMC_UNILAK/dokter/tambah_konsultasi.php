<?php
require_once '../config.php';

// Check if user is logged in and is Dokter
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Dokter') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

// Get patients who haven't been examined yet (not in consultations table)
$patients_query = "SELECT p.patient_id, p.nik, p.full_name, p.phone, p.status, p.created_at 
                   FROM patients p 
                   WHERE NOT EXISTS (
                       SELECT 1 FROM consultations c WHERE c.patient_id = p.patient_id
                   )
                   ORDER BY p.created_at DESC";
$patients_result = mysqli_query($conn, $patients_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Auto-generate Consultation ID
    $query = "SELECT consultation_id FROM consultations ORDER BY consultation_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['consultation_id'];
        $number = intval(substr($last_id, 1)) + 1;
        $consultation_id = 'C' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        $consultation_id = 'C001';
    }
    
    // Get patient data to extract registration date
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $patient_query = "SELECT nik, created_at FROM patients WHERE patient_id = '$patient_id'";
    $patient_result = mysqli_query($conn, $patient_query);
    $patient_data = mysqli_fetch_assoc($patient_result);
    
    // Convert created_at to DDMMYY format
    $created_date = new DateTime($patient_data['created_at']);
    $consultation_date = $created_date->format('dmy'); // DDMMYY
    
    $nik = $patient_data['nik'];
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $medication = mysqli_real_escape_string($conn, $_POST['medication']);
    $doctor_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO consultations (consultation_id, consultation_date, patient_id, nik, complaint, diagnosis, medication, doctor_id) 
              VALUES ('$consultation_id', '$consultation_date', '$patient_id', '$nik', '$complaint', '$diagnosis', '$medication', '$doctor_id')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data konsultasi berhasil ditambahkan dengan ID: ' . $consultation_id;
    } else {
        $error = 'Gagal menambahkan konsultasi: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Konsultasi - Unilak Medical Center</title>
    <link rel="stylesheet" href="../style.css">
    <script>
        // Show patient data when patient is selected
        function showPatientData() {
            var patientSelect = document.getElementById('patient_select');
            var selectedOption = patientSelect.options[patientSelect.selectedIndex];
            
            if (selectedOption.value) {
                document.getElementById('nama').value = selectedOption.getAttribute('data-nama');
                document.getElementById('phone').value = selectedOption.getAttribute('data-phone');
                document.getElementById('status').value = selectedOption.getAttribute('data-status');
                document.getElementById('patient_info').style.display = 'block';
                document.getElementById('consultation_section').style.display = 'block';
            } else {
                document.getElementById('patient_info').style.display = 'none';
                document.getElementById('consultation_section').style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="user-info">
            Logged in as: <strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['role']; ?>)
            | <a href="../logout.php">Logout</a>
        </div>
        
        <h1>Tambah Data Konsultasi</h1>
        
        <div class="navigation">
            <a href="dasbor.php">Dashboard</a>
            <a href="daftar_pasien.php">Data Pasien</a>
            <a href="daftar_konsultasi.php">Data Konsultasi</a>
            <a href="tambah_konsultasi.php">Tambah Konsultasi</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="daftar_konsultasi.php">Lihat Data Konsultasi</a>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>ID Pasien *</label>
                <select name="patient_id" required id="patient_select" onchange="showPatientData()">
                    <option value="">-- Pilih Pasien --</option>
                    <?php 
                    if (mysqli_num_rows($patients_result) > 0):
                        while ($patient = mysqli_fetch_assoc($patients_result)): 
                    ?>
                        <option value="<?php echo $patient['patient_id']; ?>"
                                data-nama="<?php echo htmlspecialchars($patient['full_name']); ?>"
                                data-phone="<?php echo htmlspecialchars($patient['phone']); ?>"
                                data-status="<?php echo $patient['status']; ?>">
                            <?php echo $patient['patient_id'] . ' - ' . $patient['full_name']; ?>
                        </option>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <option value="" disabled>Tidak ada pasien yang belum diperiksa</option>
                    <?php endif; ?>
                </select>
                <small style="color: #666;">Hanya menampilkan pasien yang belum diperiksa</small>
            </div>
            
            <div id="patient_info" style="display: none;">
                <h3>Data Pasien (Otomatis Terisi):</h3>
                
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" id="nama" readonly style="background: #f0f0f0;">
                </div>
                
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" id="phone" readonly style="background: #f0f0f0;">
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" id="status" readonly style="background: #f0f0f0;">
                </div>
            </div>
            
            <div id="consultation_section" style="display: none;">
                <h3>Data Konsultasi (Diisi oleh Dokter):</h3>
                
                <div class="form-group">
                    <label>Keluhan Pasien *</label>
                    <textarea name="complaint" required placeholder="Keluhan utama pasien"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Hasil Diagnosis Dokter *</label>
                    <textarea name="diagnosis" required placeholder="Diagnosis dokter"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Resep Obat *</label>
                    <textarea name="medication" required placeholder="Nama obat dan dosis"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Simpan Konsultasi</button>
                <a href="daftar_konsultasi.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
