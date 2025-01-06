<?php
require '../config/db_connect.php';

// Periksa apakah admin sudah login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah ID diberikan melalui URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = $_GET['id'];

// Periksa apakah data inventory dengan ID tersebut ada di database
$sql_check = "SELECT * FROM inventory WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param('i', $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    header("Location: admin_dashboard.php");
    exit();
}

// Hapus data dari database
$sql_delete = "DELETE FROM inventory WHERE id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param('i', $id);

if ($stmt_delete->execute()) {
    header("Location: admin_dashboard.php?message=Inventory+deleted+successfully");
    exit();
} else {
    header("Location: admin_dashboard.php?error=Failed+to+delete+inventory");
    exit();
}
?>
