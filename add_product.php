<?php
session_start();
include("conex.php");

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

$errors = [];
$products = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    extract($_POST);
    $imageName = '';

    // Field validation
    if (empty($titre)) {
        $errors['titre'] = "The title field is required.";
    }
    if (empty($description)) {
        $errors['description'] = "The description field is required.";
    }
    if (empty($prix) || !is_numeric($prix)) {
        $errors['prix'] = "The price field is required and must be a valid number.";
    }

    // Image validation
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
            $imageName = uniqid() . "_" . basename($image['name']);
            $uploadPath = $uploadDir . $imageName;

            if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
                $errors['image'] = "Failed to upload the image.";
            }
        }
    } else {
        $errors['image'] = "The image is required.";
    }

    // insert
    if (empty($errors)) {
        try {
            $stmt = $con->prepare("INSERT INTO produits (titre, description, prix, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titre, $description, $prix, $imageName]);
            $_SESSION['message'] = "Product successfully added.";
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $errors['db'] = "Database error: " . $e->getMessage();
        }
    }
}
try {
    $stmt = $con->query("SELECT * FROM produits");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching products: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Product Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <div class="admin-header">
            <h1>Product Management</h1>
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
                    <input type="text" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Price</label>
                    <input type="number" id="prix" name="prix" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <small class="form-text">Accepted Formats: JPG, JPEG, PNG, SVG. Maximum Size: 5MB</small>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Add Product</button>
                </div>
            </form>
        </div>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="price-tag"><?= number_format($product['prix'], 2); ?>€</div>
                    <img src="images/<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['titre']); ?>" class="product-image">
                    <div class="product-details">
                        <h3><?= htmlspecialchars($product['titre']); ?></h3>
                        <p><?= htmlspecialchars(substr($product['description'], 0, 100)) . (strlen($product['description']) > 100 ? '...' : ''); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
