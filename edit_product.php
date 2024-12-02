<?php
session_start();
include("conex.php");

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

$errors = [];
$product = null;

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    $_SESSION['error'] = "Invalid product ID";
    header("Location: dashboard.php");
    exit();
}

try {
    $stmt = $con->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $_SESSION['error'] = "Product not found";
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching product: " . $e->getMessage();
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    extract($_POST);
    $imageName = $product['image_url']; 
    if (empty($titre)) {
        $errors['titre'] = "The title field is required.";
    }
    if (empty($description)) {
        $errors['description'] = "The description field is required.";
    }
    if (empty($prix) || !is_numeric($prix)) {
        $errors['prix'] = "The price field is required and must be a valid number.";
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $allowed_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg+xml'];
        if (!in_array($image['type'], $allowed_types)) {
            $errors['image'] = "Invalid file type. Accepted: JPG, JPEG, PNG, SVG.";
        } elseif ($image['size'] > 5000000) {
            $errors['image'] = "The image must not exceed 5 MB.";
        } else {
            $uploadDir = __DIR__ . '/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($product['image_url'] && file_exists($uploadDir . $product['image_url'])) {
                unlink($uploadDir . $product['image_url']);
            }
            
            $imageName = uniqid() . "_" . basename($image['name']);
            $uploadPath = $uploadDir . $imageName;

            if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
                $errors['image'] = "Failed to upload the image.";
            }
        }
    }

    // Update product
    if (empty($errors)) {
        try {
            $stmt = $con->prepare("UPDATE produits SET titre = ?, description = ?, prix = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$titre, $description, $prix, $imageName, $product_id]);
            $_SESSION['message'] = "Product successfully updated.";
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $errors['db'] = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <div class="admin-header">
            <h1>Edit Product</h1>
            <div class="header-buttons">
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-delete">Disconnect</a>
            </div>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message message-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="message message-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="product-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Product Title</label>
                    <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($product['titre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="prix">Price</label>
                    <input type="number" id="prix" name="prix" step="0.01" value="<?= htmlspecialchars($product['prix']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <div class="current-image">
                        <img src="images/<?= htmlspecialchars($product['image_url']); ?>" alt="Current product image" style="max-width: 200px;">
                        <p>Current image</p>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small class="form-text">Leave empty to keep current image. Accepted Formats: JPG, JPEG, PNG, SVG. Maximum Size: 5MB</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Update Product</button>
                    <a href="dashboard.php" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>