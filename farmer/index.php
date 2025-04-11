<?php 
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

// Fetch all subsidies from the database
$stmt = $pdo->query("SELECT * FROM subsidies"); // Assuming 'subsidies' is the correct table
$subsidies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Portal - Policies</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Farmer Portal</h1>
            <nav class="mt-2">
                <ul class="flex space-x-4">
                    <li><a href="#policies" class="hover:underline">Policies</a></li>
                    <li><a href="subsides.php" class="hover:underline">Subsidies</a></li>
                    <li><a href="loans.php" class="hover:underline">Loans</a></li>
                    <a href="farmer_dashboard.php" class="btn-primary">Dashboard</a>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Policies Section -->
        <section id="policies" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Government Policies</h2>
            <div id="policyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($policies as $policy): ?>
                    <?php if (in_array('Policy', explode(',', $policy['type']))): ?>
                        <div class="bg-white p-4 rounded shadow-md">
                            <h3 class="font-bold"><?= htmlspecialchars($policy['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($policy['description'])) ?></p>
                            <div class="mt-2">
                                <strong>Region:</strong> <?= htmlspecialchars($policy['region']) ?>
                            </div>
                            <div class="mt-2">
                                <a href="<?= htmlspecialchars($policy['pdf_link']) ?>" target="_blank" class="text-blue-600">View PDF</a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Subsidies Section -->
        <section id="subsidies" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Subsidies</h2>
            <div id="subsidyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($subsidies as $subsidy): ?>
                    <div class="bg-white p-4 rounded shadow-md">
                        <h3 class="font-bold"><?= htmlspecialchars($subsidy['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($subsidy['description'])) ?></p>
                        <div class="mt-2">
                            <strong>Eligibility Criteria:</strong>
                            <p><?= nl2br(htmlspecialchars($subsidy['eligibility_criteria'])) ?></p>
                        </div>
                        <div class="mt-2">
                            <strong>Application Process:</strong>
                            <p><?= nl2br(htmlspecialchars($subsidy['application_process'])) ?></p>
                        </div>
                        <div class="mt-2">
                            <strong>Created At:</strong> <?= date('F j, Y', strtotime($subsidy['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Loans Section -->
        <section id="loans" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Loan Applications</h2>
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
                        <?php 
                        // Fetch applied loans from the database
                        $stmt = $pdo->query("SELECT * FROM loans");
                        $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($loans as $loan): ?>
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

    <script src="scripts.js"></script>
</body>
</html>
