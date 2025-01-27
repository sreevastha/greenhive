<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || getUserRole() !== 'Customer') {
    echo json_encode(['success' => false, 'message' => 'Not logged in as customer.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart_data = $stmt->fetch();
    $cart_count = $cart_data['total_items'] ?? 0;

    echo json_encode(['success' => true, 'cart_count' => $cart_count]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching cart count.']);
}
?>