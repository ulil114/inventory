<?php
// Menyertakan koneksi database
require 'config/db_connect.php';

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    
    // Menetapkan role admin secara default (bisa diganti sesuai kebutuhan)
    $role = 'admin'; // Atur 'admin' sebagai default role
    
    // Validasi password
    if ($password !== $password_confirm) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data ke database
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
        if (mysqli_query($conn, $sql)) {
            $success = "Registrasi berhasil sebagai admin! Silakan <a href='login.php'>login</a>.";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Register</h2>
        </div>
        
        <!-- Menampilkan pesan error atau sukses -->
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <!-- Form Registrasi -->
        <form action="register.php" method="POST">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="email" name="email" class="input-field" placeholder="Email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <input type="password" name="password_confirm" class="input-field" placeholder="Confirm Password" required>
            <button type="submit" class="button">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
