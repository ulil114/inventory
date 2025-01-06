<?php
require '../config/db_connect.php';

// Periksa apakah admin sudah login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah ID inventory disediakan
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$error = '';
$success = '';

// Ambil data inventory berdasarkan ID
$sql = "SELECT * FROM inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$inventory = $result->fetch_assoc();

// Proses form jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    // Validasi input
    if (empty($title) || empty($description)) {
        $error = "All fields are required.";
    } else {
        // Proses upload gambar jika ada
        $image_name = $inventory['image'];
        if ($image['error'] == 0) {
            $target_dir = "../assets/images/";
            $image_name = uniqid() . "_" . basename($image['name']);
            $target_file = $target_dir . $image_name;

            // Validasi file
            $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (!in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                $error = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
            } elseif (!move_uploaded_file($image['tmp_name'], $target_file)) {
                $error = "Failed to upload image.";
            } else {
                // Hapus gambar lama jika berhasil upload gambar baru
                if ($inventory['image'] && file_exists($target_dir . $inventory['image'])) {
                    unlink($target_dir . $inventory['image']);
                }
            }
        }

        // Update data di database
        if (empty($error)) {
            $sql_update = "UPDATE inventory SET title = ?, description = ?, image = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('sssi', $title, $description, $image_name, $id);

            if ($stmt_update->execute()) {
                $success = "Inventory updated successfully!";
                // Refresh data inventory
                $inventory['title'] = $title;
                $inventory['description'] = $description;
                $inventory['image'] = $image_name;
            } else {
                $error = "Failed to update inventory. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea, button {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Inventory</h2>

        <!-- Menampilkan pesan error atau sukses -->
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($inventory['title']) ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($inventory['description']) ?></textarea>

            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <?php if ($inventory['image']): ?>
                <img src="../assets/images/<?= htmlspecialchars($inventory['image']) ?>" alt="Current Image">
            <?php endif; ?>

            <button type="submit">Update Inventory</button>
        </form>
    </div>
</body>
</html>
