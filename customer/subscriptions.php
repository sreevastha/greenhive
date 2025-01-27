<?php
require_once __DIR__ . '/../includes/config.php'; // Corrected path
require_once __DIR__ . '/../includes/db_connect.php'; // Corrected path
require_once __DIR__ . '/../includes/functions.php'; // Corrected path

// Check if the user is logged in
if (!isLoggedIn() || getUserRole() !== 'Customer') {
    redirectWith('/login.php', 'Please login to view your subscriptions.', 'error');
}

$user_id = $_SESSION['user_id'];

// Fetch the user's subscription details
$stmt = $pdo->prepare("
    SELECT * FROM subscriptions 
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$subscriptions = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php'; // Corrected path
?>

<!-- Subscriptions Section -->
<div class="container mx-auto px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-center mb-8">Your Subscriptions</h2>
    <?php if (empty($subscriptions)): ?>
        <p class="text-gray-600 text-center">You have no active subscriptions.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($subscriptions as $subscription): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-bold mb-2">Subscription Details</h3>
                    <p class="text-gray-600">Height: <?= $subscription['height'] ?> cm</p>
                    <p class="text-gray-600">Weight: <?= $subscription['weight'] ?> kg</p>
                    <p class="text-gray-600">Age: <?= $subscription['age'] ?></p>
                    <p class="text-gray-600">Health Goals: <?= htmlspecialchars($subscription['health_goals']) ?></p>
                    <p class="text-gray-600">Plan: <?= $subscription['plan'] ?></p>
                    <p class="text-gray-600">Start Date: <?= date('d M Y', strtotime($subscription['start_date'])) ?></p>
                    <p class="text-gray-600">End Date: <?= date('d M Y', strtotime($subscription['end_date'])) ?></p>
                    <p class="text-gray-600">Notes: <?= htmlspecialchars($subscription['notes']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; // Corrected path ?>