<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenHive - Sustainable Food Ecosystem</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="bg-green-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= SITE_URL ?>" class="text-white text-lg font-bold flex items-center">
                <i class="fas fa-leaf mr-2"></i> GreenHive
            </a>
            <button class="text-white lg:hidden block" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="hidden lg:flex lg:items-center lg:space-x-6 text-white" id="nav-links">
                <li><a href="<?= SITE_URL ?>/products.php" class="hover:text-green-200">Products</a></li>
                <li><a href="<?= SITE_URL ?>/meals.php" class="hover:text-green-200">Meal Plans</a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (getUserRole() === 'Customer'): ?>
                        <li><a href="<?= SITE_URL ?>/cart.php" class="hover:text-green-200">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a></li>
                    <?php endif; ?>
                    <li class="relative group">
                        <a href="#" id="user-menu-btn" class="hover:text-green-200 flex items-center">
                            <i class="fas fa-user mr-2"></i> 
                            <?= $_SESSION['user_name'] ?>
                        </a>
                        <ul class="hidden bg-white text-black p-2 mt-2 rounded shadow-lg" id="user-menu-content">
                            <?php if (getUserRole() === 'Admin'): ?>
                                <li><a href="<?= SITE_URL ?>/admin/admin_dashboard.php" class="block px-4 py-2 hover:bg-gray-200">Dashboard</a></li>
                            <?php elseif (getUserRole() === 'Farmer'): ?>
                                <li><a href="<?= SITE_URL ?>/farmer/farmer_dashboard.php" class="block px-4 py-2 hover:bg-gray-200">Dashboard</a></li>
                            <?php else: ?>
                                <li><a href="<?= SITE_URL ?>/customer/profile.php" class="block px-4 py-2 hover:bg-gray-200">My Profile</a></li>
                                <li><a href="<?= SITE_URL ?>/customer/orders.php" class="block px-4 py-2 hover:bg-gray-200">My Orders</a></li>
                                <li><a href="<?= SITE_URL ?>/customer/subscriptions.php" class="block px-4 py-2 hover:bg-gray-200">My Subscriptions</a></li>
                            <?php endif; ?>
                            <li><a href="<?= SITE_URL ?>/logout.php" class="block px-4 py-2 hover:bg-gray-200">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?= SITE_URL ?>/login.php" class="hover:text-green-200">Login</a></li>
                    <li><a href="<?= SITE_URL ?>/signup.php" class="hover:text-green-200">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container mx-auto p-4">
    <!-- Your main content goes here -->
    </main>

    <script>
        document.getElementById('mobile-menu-btn').onclick = function() {
            var navLinks = document.getElementById('nav-links');
            navLinks.classList.toggle('hidden');
        };

        document.getElementById('user-menu-btn').onclick = function() {
            var userMenuContent = document.getElementById('user-menu-content');
            userMenuContent.classList.toggle('hidden');
        };
    </script>
</body>
</html>
