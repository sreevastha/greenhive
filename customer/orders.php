<?php
require_once __DIR__ . '/../includes/config.php'; // Corrected path
require_once __DIR__ . '/../includes/db_connect.php'; // Corrected path
require_once __DIR__ . '/../includes/functions.php'; // Corrected path

// Check if the user is logged in
if (!isLoggedIn() || getUserRole() !== 'Customer') {
    redirectWith('/login.php', 'Please login to view your orders.', 'error');
}

$user_id = $_SESSION['user_id'];

// Fetch all orders for the logged-in user
$stmt = $pdo->prepare("
    SELECT o.*, p.name as product_name, p.image_url 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php'; // Corrected path
?>

<!-- Orders Section -->
<div class="container mx-auto px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-center mb-8">Your Orders</h2>
    <?php if (empty($orders)): ?>
        <p class="text-gray-600 text-center">You have no orders yet.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <img src="<?= htmlspecialchars($order['image_url']) ?>" 
                         alt="<?= htmlspecialchars($order['product_name']) ?>" 
                         class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($order['product_name']) ?></h3>
                    <p class="text-gray-600">Quantity: <?= $order['quantity'] ?></p>
                    <p class="text-gray-600">Total Price: ₹<?= number_format($order['total_price'], 2) ?></p>
                    <p class="text-gray-600">Status: <span class="font-semibold"><?= $order['status'] ?></span></p>
                    <p class="text-gray-600">Order Date: <?= date('d M Y', strtotime($order['created_at'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; // Corrected path ?>