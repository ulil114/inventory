<?php
require '../config/db_connect.php';

// Mulai sesi untuk notifikasi
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Validasi apakah ID diberikan
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data inventory berdasarkan ID
    $sql = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $inventory = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Inventory item not found.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        img {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        p {
            margin: 10px 0;
            font-size: 16px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inventory Details</h1>
        <img src="../assets/images/<?= htmlspecialchars($inventory['image']) ?>" alt="<?= htmlspecialchars($inventory['title']) ?>">
        <p><strong>Title:</strong> <?= htmlspecialchars($inventory['title']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($inventory['description']) ?></p>
        <p><strong>Created At:</strong> <?= date('d M Y, H:i', strtotime($inventory['created_at'])) ?></p>
        <p><strong>Last Updated:</strong> <?= date('d M Y, H:i', strtotime($inventory['updated_at'])) ?></p>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
