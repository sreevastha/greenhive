<?php
// Input validation
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get user role
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

// Redirect with message
function redirectWith($url, $message = '', $type = 'success') {
    if ($message) {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }
    header("Location: " . SITE_URL . $url);
    exit();
}

// Display flash message
function showMessage() {
    if (isset($_SESSION['flash'])) {
        $message = $_SESSION['flash']['message'];
        $type = $_SESSION['flash']['type'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-{$type}'>{$message}</div>";
    }
    return '';
}

function getCartCount() {
    global $pdo; // Access the database connection
    if (isLoggedIn() && getUserRole() === 'Customer') {
        $user_id = $_SESSION['user_id'];
        try {
            $stmt = $pdo->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $cart_data = $stmt->fetch();
            return $cart_data['total_items'] ?? 0; // Return 0 if cart is empty
        } catch (PDOException $e) {
            error_log("Error fetching cart count: " . $e->getMessage());
            return 0; // Return 0 on error to avoid breaking the page
        }
    }
    return 0; // Return 0 if not logged in as customer
}
?>