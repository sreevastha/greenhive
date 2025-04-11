<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

// Fetch all applied loans
$stmt = $pdo->query("SELECT * FROM loans");
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Loans</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Applied Loans</h1>
            <nav class="mt-2">
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="farmer_dashboard.php" class="hover:underline">Dashboard</a></li>
                    <li><a href="apply_loan.php" class="text-white bg-blue-500 px-4 py-2 rounded">Apply for Loan</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section id="loanList" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Applied Loan Records</h2>
            <div class="bg-white p-4 rounded shadow-md">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Loan ID</th>
                            <th class="px-4 py-2">Farm Size</th>
                            <th class="px-4 py-2">Annual Income</th>
                            <th class="px-4 py-2">Loan Amount</th>
                            <th class="px-4 py-2">Purpose</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                            <tr>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['id']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['farm_size']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['annual_income']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['loan_amount']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['purpose']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['status']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($loan['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
