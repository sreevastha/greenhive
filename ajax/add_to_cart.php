<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in and is a customer
if (!isLoggedIn() || getUserRole() !== 'Customer') {
    echo json_encode(['success' => false]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $product_id = validateInput($data['product_id']);
    $quantity = intval($data['quantity']);
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($product_id) || $quantity <= 0) {
        echo json_encode(['success' => false]);
        exit;
    }

    // Check if product exists and has enough stock
    $stmt = $pdo->prepare("SELECT name, price, total_stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false]);
        exit;
    }

    if ($quantity > $product['total_stock']) {
        echo json_encode(['success' => false]);
        exit;
    }

    // Check if item already exists in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing_item = $stmt->fetch();

    try {
        if ($existing_item) {
            // Update quantity if item exists
            $new_quantity = $existing_item['quantity'] + $quantity;

            //check if new_quantity exceeds total_stock
            if ($new_quantity > $product['total_stock']) {
                echo json_encode(['success' => false]);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $existing_item['id']]);
        } else {
            // Insert new item into cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }

        // Calculate the total number of items in the cart
        $stmt = $pdo->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_data = $stmt->fetch();
        $cart_count = $cart_data['total_items'] ?? 0; // Use 0 if cart is empty

        echo json_encode(['success' => true, 'cart_count' => $cart_count]);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false]);
}
?>