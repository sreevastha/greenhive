<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle loan creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_loan'])) {
    $user_id = $_POST['user_id'];
    $farm_size = $_POST['farm_size'];
    $annual_income = $_POST['annual_income'];
    $loan_amount = $_POST['loan_amount'];
    $purpose = $_POST['purpose'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO loans (user_id, farm_size, annual_income, loan_amount, purpose, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $farm_size, $annual_income, $loan_amount, $purpose, $status]);
    redirectWith('/admin/manage_loans.php', 'Loan added successfully!', 'success');
}

// Handle loan deletion
if (isset($_GET['delete_loan'])) {
    $loanId = $_GET['delete_loan'];
    $stmt = $pdo->prepare("DELETE FROM loans WHERE id = ?");
    $stmt->execute([$loanId]);
    redirectWith('/admin/manage_loans.php', 'Loan deleted successfully!', 'success');
}

// Handle loan editing
if (isset($_GET['edit_loan'])) {
    $loanId = $_GET['edit_loan'];
    $stmt = $pdo->prepare("SELECT * FROM loans WHERE id = ?");
    $stmt->execute([$loanId]);
    $loanToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_loan'])) {
    $loanId = $_POST['loan_id']; // Get the loan ID from the hidden input
    $user_id = $_POST['user_id'];
    $farm_size = $_POST['farm_size'];
    $annual_income = $_POST['annual_income'];
    $loan_amount = $_POST['loan_amount'];
    $purpose = $_POST['purpose'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE loans SET user_id = ?, farm_size = ?, annual_income = ?, loan_amount = ?, purpose = ?, status = ? WHERE id = ?");
    $stmt->execute([$user_id, $farm_size, $annual_income, $loan_amount, $purpose, $status, $loanId]);
    redirectWith('/admin/manage_loans.php', 'Loan updated successfully!', 'success');
}

// Fetch loans for displaying
$stmt = $pdo->query("SELECT * FROM loans");
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Loans</h1>

    <!-- Add/Edit Loan Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($loanToEdit) ? 'Edit Loan' : 'Add Loan' ?></h2>

        <!-- Hidden input for loan ID when editing -->
        <?php if (isset($loanToEdit)): ?>
            <input type="hidden" name="loan_id" value="<?= $loanToEdit['id'] ?>">
        <?php endif; ?>

        <input type="number" name="user_id" placeholder="User ID" required class="border p-2 mb-2 w-full" value="<?= isset($loanToEdit) ? htmlspecialchars($loanToEdit['user_id']) : '' ?>">

        <input type="number" step="any" name="farm_size" placeholder="Farm Size" required class="border p-2 mb-2 w-full" value="<?= isset($loanToEdit) ? htmlspecialchars($loanToEdit['farm_size']) : '' ?>">

        <input type="number" step="any" name="annual_income" placeholder="Annual Income" required class="border p-2 mb-2 w-full" value="<?= isset($loanToEdit) ? htmlspecialchars($loanToEdit['annual_income']) : '' ?>">

        <input type="number" step="any" name="loan_amount" placeholder="Loan Amount" required class="border p-2 mb-2 w-full" value="<?= isset($loanToEdit) ? htmlspecialchars($loanToEdit['loan_amount']) : '' ?>">

        <textarea name="purpose" placeholder="Purpose" required class="border p-2 mb-2 w-full"><?= isset($loanToEdit) ? htmlspecialchars($loanToEdit['purpose']) : '' ?></textarea>

        <select name="status" class="border p-2 mb-2 w-full">
            <option value="Pending" <?= isset($loanToEdit) && $loanToEdit['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Approved" <?= isset($loanToEdit) && $loanToEdit['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="Rejected" <?= isset($loanToEdit) && $loanToEdit['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>

        <button type="submit" name="<?= isset($loanToEdit) ? 'update_loan' : 'add_loan' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($loanToEdit) ? 'Update Loan' : 'Add Loan' ?></button>
    </form>

    <!-- Loans Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">User ID</th>
                <th class="border">Farm Size</th>
                <th class="border">Annual Income</th>
                <th class="border">Loan Amount</th>
                <th class="border">Purpose</th>
                <th class="border">Status</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td class="border"><?= $loan['id'] ?></td>
                    <td class="border"><?= $loan['user_id'] ?></td>
                    <td class="border"><?= $loan['farm_size'] ?></td>
                    <td class="border"><?= $loan['annual_income'] ?></td>
                    <td class="border"><?= $loan['loan_amount'] ?></td>
                    <td class="border"><?= htmlspecialchars($loan['purpose']) ?></td>
                    <td class="border"><?= htmlspecialchars($loan['status']) ?></td>
                    <td class="border">
                        <a href="?edit_loan=<?= $loan['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_loan=<?= $loan['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
