<?php
require '../config/db_connect.php';

// Periksa apakah admin sudah login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    // Validate form inputs
    if (empty($title) || empty($description) || empty($image['name'])) {
        $error_message = "All fields are required!";
    } else {
        // Handle image upload
        $image_name = time() . '_' . basename($image['name']);
        $target_dir = "../assets/images/";
        $target_file = $target_dir . $image_name;
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file type
        if (in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                // Insert data into the database
                $sql = "INSERT INTO inventory (title, description, image, created_at) 
                        VALUES ('$title', '$description', '$image_name', NOW())";
                if ($conn->query($sql)) {
                    $success_message = "Product added successfully!";
                } else {
                    $error_message = "Failed to add product.";
                }
            } else {
                $error_message = "Image upload failed.";
            }
        } else {
            $error_message = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group textarea {
            height: 100px;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #28a745;
            color: white;
        }
        .message.error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>

    <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form action="add_inventory.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Product Title</label>
            <input type="text" id="title" name="title" value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Add Product</button>
    </form>

    <a href="dashboard.php" class="btn btn-danger">Cancel</a>
</div>

</body>
</html>
