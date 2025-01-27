<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);
    redirectWith('/admin/manage_orders.php', 'Order status updated successfully!', 'success');
}

// Handle order deletion
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    redirectWith('/admin/manage_orders.php', 'Order deleted successfully!', 'success');
}

// Fetch orders with user and product details
$stmt = $pdo->query("
    SELECT 
        o.id,
        o.quantity,
        o.total_price,
        o.status,
        o.created_at,
        u.name AS user_name,
        u.email AS user_email,
        p.name AS product_name,
        p.price AS product_price
    FROM orders AS o
    JOIN users AS u ON o.user_id = u.id
    JOIN products AS p ON o.product_id = p.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Orders</h1>

    <!-- Orders Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">User</th>
                <th class="border">Product</th>
                <th class="border">Quantity</th>
                <th class="border">Total Price</th>
                <th class="border">Status</th>
                <th class="border">Ordered At</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="border"><?= $order['id'] ?></td>
                    <td class="border">
                        <?= htmlspecialchars($order['user_name']) ?> (<?= htmlspecialchars($order['user_email']) ?>)
                    </td>
                    <td class="border"><?= htmlspecialchars($order['product_name']) ?></td>
                    <td class="border"><?= $order['quantity'] ?></td>
                    <td class="border"><?= $order['total_price'] ?></td>
                    <td class="border">
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="border p-2" onchange="this.form.submit()">
                                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Confirmed" <?= $order['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                            <input type="hidden" name="update_order_status">
                        </form>
                    </td>
                    <td class="border"><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                    <td class="border">
                        <a href="?delete_order=<?= $order['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>