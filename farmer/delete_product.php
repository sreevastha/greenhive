<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$user_role = getUserRole();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    try {
        // Admin can delete any product, Farmer can delete only their own
        if ($user_role === 'Admin') {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
        } else if ($user_role === 'Farmer') {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND farmer_id = ?");
            $stmt->execute([$product_id, $user_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized action']);
            exit;
        }

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found or not authorized']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting product: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>