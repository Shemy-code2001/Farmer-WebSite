<?php
session_start();
include("conex.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([]);
    exit();
}

$notifications = [];

try {
    $stmt = $con->query("SELECT COUNT(*) as new_orders FROM commandes WHERE status = 'en attente'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['new_orders'] > 0) {
        $notifications[] = [
            'icon' => '🛒',
            'message' => "Vous avez {$result['new_orders']} nouvelles commandes en attente"
        ];
    }
} catch (PDOException $e) {
}

try {
    $stmt = $con->query("SELECT COUNT(*) as low_stock FROM produits WHERE stock < 10");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['low_stock'] > 0) {
        $notifications[] = [
            'icon' => '⚠️',
            'message' => "Attention, {$result['low_stock']} produits ont un stock bas"
        ];
    }
} catch (PDOException $e) {
}

try {
    $stmt = $con->query("SELECT COUNT(*) as new_users FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['new_users'] > 0) {
        $notifications[] = [
            'icon' => '👥',
            'message' => "Vous avez {$result['new_users']} nouveaux utilisateurs aujourd'hui"
        ];
    }
} catch (PDOException $e) {
}

header('Content-Type: application/json');
echo json_encode($notifications);
exit();
?>