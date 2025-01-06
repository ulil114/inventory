<?php
require '../config/db_connect.php';

// Periksa apakah admin sudah login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data inventory dari database
$sql = "SELECT * FROM inventory ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-add {
            background-color: #28a745;
        }
        .btn-logout {
            background-color: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="../index.php">Home</a>
        <a href="add_inventory.php" class="btn btn-add">Add Inventory</a>
        <a href="../logout.php" class="btn btn-logout">Logout</a>
    </div>

    <div class="container">
        <h2>Inventory Management</h2>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td>
                        <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" style="width: 80px; height: 80px; object-fit: cover;">
                    </td>
                    <td><?= htmlspecialchars(substr($row['description'], 0, 50)) ?>...</td>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?></td>
                   

                    <td>
                
                        <a href="edit_inventory.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="delete_inventory.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure to delete this item?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No inventory items found. Click "Add Inventory" to create one.</p>
        <?php endif; ?>
    </div>
</body>
</html>
