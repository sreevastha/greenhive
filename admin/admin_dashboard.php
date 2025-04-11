 
<?php
require_once __DIR__ . '/../includes/config.php'; // Corrected path
require_once __DIR__ . '/../includes/db_connect.php'; // Corrected path
require_once __DIR__ . '/../includes/functions.php'; // Corrected path

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Corrected path
?>

<!-- Admin Dashboard -->
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white p-4">
        <h2 class="text-xl font-bold mb-6">Admin Dashboard</h2>
        <ul class="space-y-2">
            <li><a href="admin_dashboard.php" class="block hover:bg-gray-700 p-2 rounded">Dashboard</a></li>
            <li><a href="manage_users.php" class="block hover:bg-gray-700 p-2 rounded">Manage Users</a></li>
            <li><a href="manage_products.php" class="block hover:bg-gray-700 p-2 rounded">Manage Products</a></li>
            <li><a href="manage_subscriptions.php" class="block hover:bg-gray-700 p-2 rounded">Manage Subscriptions</a></li>
            <li><a href="manage_meal_plans.php" class="block hover:bg-gray-700 p-2 rounded">Manage Meal Plans</a></li>
            <li><a href="manage_orders.php" class="block hover:bg-gray-700 p-2 rounded">Manage Orders</a></li>
            <li><a href="manage_policy.php" class="block hover:bg-gray-700 p-2 rounded">Manage policy</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Dashboard Cards -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-2">Total Users</h2>
                <p class="text-gray-600"><?= $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-2">Total Products</h2>
                <p class="text-gray-600"><?= $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-2">Total Subscriptions</h2>
                <p class="text-gray-600"><?= $pdo->query("SELECT COUNT(*) FROM subscriptions")->fetchColumn(); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; // Corrected path ?>