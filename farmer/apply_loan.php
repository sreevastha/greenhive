<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

// Start the session to access session variables
session_start();

// Check if the user is logged in and has the role of "farmer"
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the current logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Query the database to ensure the user is a "farmer"
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user has the "farmer" role
if ($user['role'] !== 'Farmer') {
    // If not a farmer, redirect to the dashboard or show an error
    header("Location: farmer_dashboard.php");
    exit();
}

// Check if the loan application form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $farm_size = $_POST['farm_size'];
    $annual_income = $_POST['annual_income'];
    $loan_amount = $_POST['loan_amount'];
    $purpose = $_POST['purpose'];

    // Insert the loan application into the database
    $stmt = $pdo->prepare("INSERT INTO loans (user_id, farm_size, annual_income, loan_amount, purpose, status, created_at) 
                           VALUES (:user_id, :farm_size, :annual_income, :loan_amount, :purpose, 'Pending', NOW())");
    $stmt->execute([
        ':user_id' => $user_id,
        ':farm_size' => $farm_size,
        ':annual_income' => $annual_income,
        ':loan_amount' => $loan_amount,
        ':purpose' => $purpose
    ]);

    // Redirect to loans page after successful application
    header("Location: loans.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Loan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Apply for Loan</h1>
            <nav class="mt-2">
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="farmer_dashboard.php" class="hover:underline">Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section id="applyLoan" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Loan Application Form</h2>
            <div class="bg-white p-4 rounded shadow-md">
                <form action="apply_loan.php" method="POST">
                    <div class="mb-4">
                        <label for="farm_size" class="block text-sm font-medium">Farm Size (Acres)</label>
                        <input type="number" id="farm_size" name="farm_size" class="w-full p-2 border rounded mt-1" required>
                    </div>
                    <div class="mb-4">
                        <label for="annual_income" class="block text-sm font-medium">Annual Income</label>
                        <input type="number" id="annual_income" name="annual_income" class="w-full p-2 border rounded mt-1" required>
                    </div>
                    <div class="mb-4">
                        <label for="loan_amount" class="block text-sm font-medium">Loan Amount</label>
                        <input type="number" id="loan_amount" name="loan_amount" class="w-full p-2 border rounded mt-1" required>
                    </div>
                    <div class="mb-4">
                        <label for="purpose" class="block text-sm font-medium">Loan Purpose</label>
                        <textarea id="purpose" name="purpose" class="w-full p-2 border rounded mt-1" required></textarea>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Application</button>
                    </div>
                </form>
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
