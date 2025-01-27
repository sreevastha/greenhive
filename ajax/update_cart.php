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
    $action = validateInput($data['action']);
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($cart_id) || !in_array($action, ['increase', 'decrease', 'set'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item or action.']);
        exit;
    }

    try {
        // Fetch cart item details
        $stmt = $pdo->prepare("
            SELECT c.quantity, p.price, p.total_stock, c.product_id
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.id = ? AND c.user_id = ?
        ");
        $stmt->execute([$cart_id, $user_id]);
        $cart_item = $stmt->fetch();

        if (!$cart_item) {
            echo json_encode(['success' => false, 'message' => 'Cart item not found.']);
            exit;
        }

        $quantity = intval($cart_item['quantity']);
        $product_id = intval($cart_item['product_id']);
        $price = floatval($cart_item['price']);
        $total_stock = intval($cart_item['total_stock']);

        if ($action === 'increase') {
            $quantity++;
        } elseif ($action === 'decrease') {
            $quantity = max(1, $quantity - 1); // Minimum quantity is 1
        } elseif ($action === 'set') {
            $quantity = intval($data['value']);
            if ($quantity <= 0) {
                $quantity = 1; // Minimum quantity is 1
            }
        }

        // Check for stock limit
        if ($quantity > $total_stock) {
            echo json_encode(['success' => false, 'message' => 'Cannot exceed available stock.']);
            exit;
        }

        // Update quantity in cart
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$quantity, $cart_id]);

        // Calculate subtotal for the item
        $subtotal = $price * $quantity;

        // Recalculate cart subtotal (for updating summary)
        $stmt = $pdo->prepare("
            SELECT SUM(p.price * c.quantity) as cart_subtotal
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_summary = $stmt->fetch();
        $cart_subtotal = floatval($cart_summary['cart_subtotal']);

        echo json_encode([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart_id' => $cart_id,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'cart_subtotal' => $cart_subtotal,
        ]);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error updating cart.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

?>