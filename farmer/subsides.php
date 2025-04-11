<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

// Start the session to access user role
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the subsidies data from the database
$stmt = $pdo->query("SELECT * FROM subsidies ORDER BY created_at DESC");
$subsidies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Subsidies</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Available Subsidies</h1>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section id="subsidiesList" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Subsidy Details</h2>
            <div class="bg-white p-4 rounded shadow-md">
                <?php if (count($subsidies) > 0): ?>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Title</th>
                                <th class="border px-4 py-2">Description</th>
                                <th class="border px-4 py-2">Eligibility</th>
                                <th class="border px-4 py-2">Application Process</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subsidies as $subsidy): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($subsidy['title']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($subsidy['description']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($subsidy['eligibility_criteria']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($subsidy['application_process']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-600">No subsidies available at the moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-green-600 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            &copy; 2023 Farmer Portal
        </div>
    </footer>
</body>
</html>
