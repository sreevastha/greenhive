<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in and is a customer
if (!isLoggedIn() || getUserRole() !== 'Customer') {
    echo json_encode(['success' => false, 'message' => 'Please login as a customer.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $cart_id = validateInput($data['cart_id']);
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($cart_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item.']);
        exit;
    }

    try {
        // Delete item from cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);

        echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error removing item from cart.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>