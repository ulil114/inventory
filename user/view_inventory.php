<?php
require 'config/db_connect.php';

// Periksa apakah parameter ID telah diberikan
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$inventory_id = intval($_GET['id']);

// Ambil data inventory berdasarkan ID
$sql = "SELECT * FROM inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inventory_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Inventory not found!";
    exit();
}

$inventory = $result->fetch_assoc();
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
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .inventory-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            color: #333;
        }
        p {
            margin: 10px 0;
            color: #555;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventory Details</h1>
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <img class="inventory-image" src="assets/images/<?= htmlspecialchars($inventory['image']) ?>" alt="<?= htmlspecialchars($inventory['title']) ?>">
        <h1><?= htmlspecialchars($inventory['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($inventory['description'])) ?></p>
        <small><i>Added on: <?= date('d M Y', strtotime($inventory['created_at'])) ?></i></small>
        <br>
        <a class="back-link" href="index.php">&larr; Back to Inventory List</a>
    </div>
</body>
</html>
