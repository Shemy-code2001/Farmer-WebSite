<?php
session_start();
include("conex.php");


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
try {
    $stmt = $con->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin'");
    $stmt->execute([$admin_id]);
    $admin_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur de récupération des informations admin.";
    header("Location: dashboard.php");
    exit();
}

// delete
if (isset($_GET['delete_product']) && is_numeric($_GET['delete_product'])) {
    try {
        $product_id = $_GET['delete_product'];
        
        // image del
        $stmt = $con->prepare("SELECT image_url FROM produits WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $image_path = __DIR__ . '/images/' . $product['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
            // prdt del
            $stmt = $con->prepare("DELETE FROM produits WHERE id = ?");
            $stmt->execute([$product_id]);
            
            $_SESSION['message'] = "Produit supprimé avec succès.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression du produit.";
    }
    
    header("Location: dashboard.php");
    exit();
}

// fetching
try {
    $stmt = $con->query("SELECT * FROM produits ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la récupération des produits.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">

        <div class="sidebar">
            <div class="sidebar-header">
                <img src="https://i.pinimg.com/736x/be/61/38/be6138527adcaf0bc42d6bd0339b25b0.jpg" alt="Logo" class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li class="active">
                        <a href="dashboard.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="add_product.php">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Product</span>
                        </a>
                    </li>
                    <li>
                        <a href="customers.php">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Disconnect</span>
                </a>
            </div>
        </div>

        <main class="main-content">
            <header class="main-header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <img src="path/to/admin-avatar.png" alt="Admin" class="admin-avatar">
                    <span><?= htmlspecialchars($admin_info['username'] ?? 'Administrateur') ?></span>
                </div>
            </header>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message message-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <section class="products-section" id="products-section">
                <h2>Products</h2>
                <div class="products-grid">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card" data-product-id="<?= $product['id'] ?>">
                                <div class="product-actions">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="dashboard.php?delete_product=<?= $product['id'] ?>" class="btn btn-delete delete-product">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                <div class="price-tag"><?= number_format($product['prix'], 2); ?>€</div>
                                <img src="images/<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['titre']); ?>" class="product-image">
                                <div class="product-details">
                                    <h3><?= htmlspecialchars($product['titre']); ?></h3>
                                    <p><?= htmlspecialchars(substr($product['description'], 0, 100)) . (strlen($product['description']) > 100 ? '...' : ''); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun produit trouvé.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
