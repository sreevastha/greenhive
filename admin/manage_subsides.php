<?php
// Include necessary files
require_once __DIR__ . '/../includes/config.php';  // Config file for settings
require_once __DIR__ . '/../includes/db_connect.php';  // DB connection file
require_once __DIR__ . '/../includes/functions.php';  // Custom functions

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

// Include header for the page
include __DIR__ . '/../includes/header.php'; 

// Handle subsidy policy creation (Add)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subsidy'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $eligibility_criteria = $_POST['eligibility_criteria'];
    $application_process = $_POST['application_process'];

    // Prepare SQL to insert new subsidy policy
    $stmt = $pdo->prepare("INSERT INTO subsidies (title, description, eligibility_criteria, application_process) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $eligibility_criteria, $application_process]);

    // Redirect to manage page with success message
    redirectWith('/admin/manage_subsides.php', 'Subsidy policy added successfully!', 'success');
}

// Handle subsidy policy deletion (Delete)
if (isset($_GET['delete_subsidy'])) {
    $subsidyId = $_GET['delete_subsidy'];

    // Prepare SQL to delete subsidy policy by ID
    $stmt = $pdo->prepare("DELETE FROM subsidies WHERE id = ?");
    $stmt->execute([$subsidyId]);

    // Redirect with success message
    redirectWith('/admin/manage_subsides.php', 'Subsidy policy deleted successfully!', 'success');
}

// Handle subsidy policy editing (Edit)
if (isset($_GET['edit_subsidy'])) {
    $subsidyId = $_GET['edit_subsidy'];

    // Fetch the subsidy data for editing
    $stmt = $pdo->prepare("SELECT * FROM subsidies WHERE id = ?");
    $stmt->execute([$subsidyId]);
    $subsidyToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subsidy'])) {
    $subsidyId = $_POST['subsidy_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $eligibility_criteria = $_POST['eligibility_criteria'];
    $application_process = $_POST['application_process'];

    // Prepare SQL to update subsidy policy
    $stmt = $pdo->prepare("UPDATE subsidies SET title = ?, description = ?, eligibility_criteria = ?, application_process = ? WHERE id = ?");
    $stmt->execute([$title, $description, $eligibility_criteria, $application_process, $subsidyId]);

    // Redirect to manage page with success message
    redirectWith('/admin/manage_subsides.php', 'Subsidy policy updated successfully!', 'success');
}

// Fetch all subsidy policies for displaying
$stmt = $pdo->query("SELECT * FROM subsidies");
$subsidies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content for Manage Subsidy Policies -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Subsidy Policies</h1>

    <!-- Add/Edit Subsidy Policy Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($subsidyToEdit) ? 'Edit Subsidy Policy' : 'Add Subsidy Policy' ?></h2>

        <!-- Hidden input for subsidy policy ID when editing -->
        <?php if (isset($subsidyToEdit)): ?>
            <input type="hidden" name="subsidy_id" value="<?= $subsidyToEdit['id'] ?>">
        <?php endif; ?>

        <!-- Form fields for Title, Description, Eligibility Criteria, Application Process -->
        <input type="text" name="title" placeholder="Subsidy Title" required class="border p-2 mb-2 w-full" value="<?= isset($subsidyToEdit) ? htmlspecialchars($subsidyToEdit['title']) : '' ?>">

        <textarea name="description" placeholder="Description" required class="border p-2 mb-2 w-full"><?= isset($subsidyToEdit) ? htmlspecialchars($subsidyToEdit['description']) : '' ?></textarea>

        <textarea name="eligibility_criteria" placeholder="Eligibility Criteria" required class="border p-2 mb-2 w-full"><?= isset($subsidyToEdit) ? htmlspecialchars($subsidyToEdit['eligibility_criteria']) : '' ?></textarea>

        <textarea name="application_process" placeholder="Application Process" required class="border p-2 mb-2 w-full"><?= isset($subsidyToEdit) ? htmlspecialchars($subsidyToEdit['application_process']) : '' ?></textarea>

        <!-- Submit button for Add or Update -->
        <button type="submit" name="<?= isset($subsidyToEdit) ? 'update_subsidy' : 'add_subsidy' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($subsidyToEdit) ? 'Update Subsidy Policy' : 'Add Subsidy Policy' ?></button>
    </form>
    <div class="mb-8">
        <a href="manage_policy.php" class="text-blue-600">Manage Policies</a> |
        <a href="manage_loans.php" class="text-blue-600">Manage Loan Policies</a>
    </div>

    <!-- Subsidy Policies Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Title</th>
                <th class="border">Description</th>
                <th class="border">Eligibility Criteria</th>
                <th class="border">Application Process</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subsidies as $subsidy): ?>
                <tr>
                    <td class="border"><?= $subsidy['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($subsidy['title']) ?></td>
                    <td class="border"><?= htmlspecialchars($subsidy['description']) ?></td>
                    <td class="border"><?= htmlspecialchars($subsidy['eligibility_criteria']) ?></td>
                    <td class="border"><?= htmlspecialchars($subsidy['application_process']) ?></td>
                    <td class="border">
                        <!-- Edit and Delete links -->
                        <a href="?edit_subsidy=<?= $subsidy['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_subsidy=<?= $subsidy['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure you want to delete this subsidy?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
// Include footer for the page
include __DIR__ . '/../includes/footer.php'; 
?>
