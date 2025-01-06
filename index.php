<?php
session_start();

// Memeriksa apakah pengguna sudah login
$is_logged_in = isset($_SESSION['username']);
?>

<?php
require 'config/db_connect.php';

// Debugging koneksi
if (!$conn) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Ambil data inventory dari database
$sql = "SELECT * FROM inventory ORDER BY created_at DESC";
$result = $conn->query($sql);

// Debugging query
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 15px;
        }
        .inventory-item {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .inventory-item img {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
        }
        .inventory-item h3 {
            margin: 0;
            color: #333;
        }
        .inventory-item p {
            margin: 5px 0;
            color: #666;
        }
        .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
        .logout-button {
            padding: 10px 20px;
            background-color: #ff4747;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-button:hover {
            background-color: #ff1f1f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to al afkar</h1>
        <!-- Link login dan register jika belum login -->
        <?php if (!$is_logged_in): ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <!-- Tombol logout jika sudah login -->
            <a href="logout.php"><button class="logout-button">Logout</button></a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h2>Available Inventory</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="inventory-item">
                    <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div>
                   
                    
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <small><i>Added on: <?= date('d M Y', strtotime($row['created_at'])) ?></i></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No inventory available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> Inventory Information. All Rights Reserved.</p>
    </div>
</body>
</html>
