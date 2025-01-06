<?php
$servername = "localhost";
$username = "root"; // Default untuk XAMPP
$password = "";     // Default tanpa password untuk XAMPP
$dbname = "sistem_inventory"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
