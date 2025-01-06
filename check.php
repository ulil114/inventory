<?php
$servername = "localhost";
$username = "root";
$password = "";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menampilkan daftar database
$result = $conn->query("SHOW DATABASES");
if ($result) {
    echo "Daftar database:<br>";
    while ($row = $result->fetch_assoc()) {
        echo $row['Database'] . "<br>";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
