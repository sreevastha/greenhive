<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle subscription creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subscription'])) {
    $user_id = $_POST['user_id'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $age = $_POST['age'];
    $health_goals = $_POST['health_goals'];
    $plan = $_POST['plan'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, height, weight, age, health_goals, plan, start_date, end_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $height, $weight, $age, $health_goals, $plan, $start_date, $end_date, $notes]);
    redirectWith('/admin/manage_subscriptions.php', 'Subscription added successfully!', 'success');
}

// Handle subscription deletion
if (isset($_GET['delete_subscription'])) {
    $subscriptionId = $_GET['delete_subscription'];
    $stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = ?");
    $stmt->execute([$subscriptionId]);
    redirectWith('/admin/manage_subscriptions.php', 'Subscription deleted successfully!', 'success');
}

// Handle subscription editing
if (isset($_GET['edit_subscription'])) {
    $subscriptionId = $_GET['edit_subscription'];
    $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE id = ?");
    $stmt->execute([$subscriptionId]);
    $subscriptionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subscription'])) {
    $subscriptionId = $_POST['subscription_id']; // Get the subscription ID from the hidden input
    $user_id = $_POST['user_id'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $age = $_POST['age'];
    $health_goals = $_POST['health_goals'];
    $plan = $_POST['plan'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("UPDATE subscriptions SET user_id = ?, height = ?, weight = ?, age = ?, health_goals = ?, plan = ?, start_date = ?, end_date = ?, notes = ? WHERE id = ?");
    $stmt->execute([$user_id, $height, $weight, $age, $health_goals, $plan, $start_date, $end_date, $notes, $subscriptionId]);
    redirectWith('/admin/manage_subscriptions.php', 'Subscription updated successfully!', 'success');
}

// Fetch subscriptions for displaying
$stmt = $pdo->query("SELECT * FROM subscriptions");
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Subscriptions</h1>

    <!-- Add/Edit Subscription Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($subscriptionToEdit) ? 'Edit Subscription' : 'Add Subscription' ?></h2>
        
        <!-- Hidden input for subscription ID when editing -->
        <?php if (isset($subscriptionToEdit)): ?>
            <input type="hidden" name="subscription_id" value="<?= $subscriptionToEdit['id'] ?>">
        <?php endif; ?>

        <input type="number" name="user_id" placeholder="User ID" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['user_id']) : '' ?>">
        <input type="number" name="height" placeholder="Height (cm)" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['height']) : '' ?>">
        <input type="number" name="weight" placeholder="Weight (kg)" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['weight']) : '' ?>">
        <input type="number" name="age" placeholder="Age" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['age']) : '' ?>">
        
        <textarea name="health_goals" placeholder="Health Goals (comma separated)" required class="border p-2 mb-2 w-full"><?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['health_goals']) : '' ?></textarea>
        
        <select name="plan" class="border p-2 mb-2 w-full">
            <option value="Weekly" <?= (isset($subscriptionToEdit) && $subscriptionToEdit['plan'] == 'Weekly') ? 'selected' : '' ?>>Weekly</option>
            <option value="Monthly" <?= (isset($subscriptionToEdit) && $subscriptionToEdit['plan'] == 'Monthly') ? 'selected' : '' ?>>Monthly</option>
        </select>
        
        <input type="date" name="start_date" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['start_date']) : '' ?>">
        <input type="date" name="end_date" required class="border p-2 mb-2 w-full" value="<?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['end_date']) : '' ?>">
        
        <textarea name="notes" placeholder="Additional Notes" class="border p-2 mb-2 w-full"><?= isset($subscriptionToEdit) ? htmlspecialchars($subscriptionToEdit['notes']) : '' ?></textarea>
        
        <button type="submit" name="<?= isset($subscriptionToEdit) ? 'update_subscription' : 'add_subscription' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($subscriptionToEdit) ? 'Update Subscription' : 'Add Subscription' ?></button>
    </form>

    <!-- Subscriptions Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">User ID</th>
                <th class="border">Height</th>
                <th class="border">Weight</th>
                <th class="border">Age</th>
                <th class="border">Health Goals</th>
                <th class="border">Plan</th>
                <th class="border">Start Date</th>
                <th class="border">End Date</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscriptions as $subscription): ?>
                <tr>
                    <td class="border"><?= $subscription['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['user_id']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['height']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['weight']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['age']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['health_goals']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['plan']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['start_date']) ?></td>
                    <td class="border"><?= htmlspecialchars($subscription['end_date']) ?></td>
                    <td class="border">
                        <a href="?edit_subscription=<?= $subscription['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_subscription=<?= $subscription['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>