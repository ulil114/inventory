<?php
session_start(); // Mulai sesi
session_destroy(); // Hancurkan sesi

// Arahkan kembali ke halaman login setelah logout
header("Location: login.php");
exit;
?>
