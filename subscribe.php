<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || getUserRole() !== 'Customer') {
    redirectWith('/login.php', 'Please login as a customer to subscribe.', 'error');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $height = validateInput($_POST['height']);
    $weight = validateInput($_POST['weight']);
    $age = validateInput($_POST['age']);
    $health_goals = implode(', ', $_POST['health_goals']); // Convert array to comma-separated string
    $notes = validateInput($_POST['notes']);

    // Insert subscription data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, height, weight, age, health_goals, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $height, $weight, $age, $health_goals, $notes]);

        redirectWith('/meals.php', 'Subscription successful!', 'success');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        redirectWith('/meals.php', 'Error subscribing. Please try again.', 'error');
    }
} else {
    redirectWith('/meals.php', 'Invalid request.', 'error');
}
?>