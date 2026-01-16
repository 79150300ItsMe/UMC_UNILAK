<?php
// Memanggil file konfigurasi database
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengamankan input dari SQL injection
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $password = md5($_POST['password']); // Enkripsi password dengan MD5
    
    // Validasi ID harus 10 digit angka
    if (empty($user_id)) {
        $error = 'ID tidak boleh kosong';
    } elseif (!ctype_digit($user_id)) {
        $error = 'ID harus berupa angka';
    } elseif (strlen($user_id) != 10) {
        $error = 'ID harus terdiri dari 10 digit angka';
    } elseif (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 10) {
        $error = 'Password harus 5-10 karakter';
    } else {
        // Query untuk mencari user berdasarkan user_id dan password
        $query = "SELECT * FROM users WHERE user_id = '$user_id' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            // Login berhasil, simpan data user ke session
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Redirect berdasarkan role pengguna
            switch ($user['role']) {
                case 'Admin':
                    header('Location: admin/dasbor.php');
                    break;
                case 'Kasir':
                    header('Location: kasir/dasbor.php');
                    break;
                case 'Dokter':
                    header('Location: dokter/dasbor.php');
                    break;
            }
            exit();
        } else {
            $error = 'ID atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Unilak Medical Center</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="container">
            <h1>Unilak Medical Center</h1>
            <h3>Sistem Informasi Klinik</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>ID Pengguna (10 digit angka)</label>
                    <input type="text" name="user_id" required placeholder="Contoh: 1234567890" maxlength="10" pattern="[0-9]{10}">
                    <small style="color: #666;">ID terdiri dari 10 digit angka</small>
                </div>
                
                <div class="form-group">
                    <label>Kata Sandi (5-10 karakter)</label>
                    <input type="password" name="password" required placeholder="Masukkan kata sandi" minlength="5" maxlength="10">
                </div>
                
                <button type="submit">Login</button>
            </form>
            
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <strong>Data Login Testing:</strong><br>
                Admin: 1234567890 / admin123<br>
                Kasir: 1234567891 / kasir123<br>
                Dokter: 1234567892 / dokter123
            </div>
        </div>
    </div>
</body>
</html>
