<?php
session_start();

// Fungsi untuk cek login
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

// Fungsi untuk logout
function logout() {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
