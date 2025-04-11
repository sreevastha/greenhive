<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle policy creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_policy'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $types = isset($_POST['type']) ? implode(',', $_POST['type']) : ''; // Store selected types as a comma-separated string
    $region = $_POST['region'];
    $pdf_link = $_POST['pdf_link'];

    $stmt = $pdo->prepare("INSERT INTO policies (title, description, type, region, pdf_link) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $types, $region, $pdf_link]);
    redirectWith('/admin/manage_policy.php', 'Policy added successfully!', 'success');
}

// Handle policy deletion
if (isset($_GET['delete_policy'])) {
    $policyId = $_GET['delete_policy'];
    $stmt = $pdo->prepare("DELETE FROM policies WHERE id = ?");
    $stmt->execute([$policyId]);
    redirectWith('/admin/manage_policy.php', 'Policy deleted successfully!', 'success');
}

// Handle policy editing
if (isset($_GET['edit_policy'])) {
    $policyId = $_GET['edit_policy'];
    $stmt = $pdo->prepare("SELECT * FROM policies WHERE id = ?");
    $stmt->execute([$policyId]);
    $policyToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_policy'])) {
    $policyId = $_POST['policy_id']; // Get the policy ID from the hidden input
    $title = $_POST['title'];
    $description = $_POST['description'];
    $types = isset($_POST['type']) ? implode(',', $_POST['type']) : ''; // Store selected types as a comma-separated string
    $region = $_POST['region'];
    $pdf_link = $_POST['pdf_link'];

    $stmt = $pdo->prepare("UPDATE policies SET title = ?, description = ?, type = ?, region = ?, pdf_link = ? WHERE id = ?");
    $stmt->execute([$title, $description, $types, $region, $pdf_link, $policyId]);
    redirectWith('/admin/manage_policy.php', 'Policy updated successfully!', 'success');
}

// Fetch policies for displaying
$stmt = $pdo->query("SELECT * FROM policies");
$policies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Policies</h1>

    <!-- Add/Edit Policy Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($policyToEdit) ? 'Edit Policy' : 'Add Policy' ?></h2>

        <!-- Hidden input for policy ID when editing -->
        <?php if (isset($policyToEdit)): ?>
            <input type="hidden" name="policy_id" value="<?= $policyToEdit['id'] ?>">
        <?php endif; ?>

        <input type="text" name="title" placeholder="Policy Title" required class="border p-2 mb-2 w-full" value="<?= isset($policyToEdit) ? htmlspecialchars($policyToEdit['title']) : '' ?>">

        <textarea name="description" placeholder="Description" required class="border p-2 mb-2 w-full"><?= isset($policyToEdit) ? htmlspecialchars($policyToEdit['description']) : '' ?></textarea>

        <!-- Multi-Select for Policy Type(s) -->
        <div class="mb-2">
            <label class="block">Select Policy Type(s):</label>
            <select name="type[]" multiple class="border p-2 mb-2 w-full">
                <option value="Loan" <?= (isset($policyToEdit) && in_array('Loan', explode(',', $policyToEdit['type']))) ? 'selected' : '' ?>>Loan</option>
                <option value="Subsidy" <?= (isset($policyToEdit) && in_array('Subsidy', explode(',', $policyToEdit['type']))) ? 'selected' : '' ?>>Subsidy</option>
                <option value="Insurance" <?= (isset($policyToEdit) && in_array('Insurance', explode(',', $policyToEdit['type']))) ? 'selected' : '' ?>>Insurance</option>
            </select>
        </div>

        <input type="text" name="region" placeholder="Region" class="border p-2 mb-2 w-full" value="<?= isset($policyToEdit) ? htmlspecialchars($policyToEdit['region']) : '' ?>">

        <input type="url" name="pdf_link" placeholder="PDF Link" class="border p-2 mb-2 w-full" value="<?= isset($policyToEdit) ? htmlspecialchars($policyToEdit['pdf_link']) : '' ?>">

        <button type="submit" name="<?= isset($policyToEdit) ? 'update_policy' : 'add_policy' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($policyToEdit) ? 'Update Policy' : 'Add Policy' ?></button>
    </form>

    <!-- Links to Manage Specific Policy Types -->
    <div class="mb-8">
        <a href="manage_subsides.php" class="text-blue-600">Manage Subsidy Policies</a> |
        <a href="manage_loans.php" class="text-blue-600">Manage Loan Policies</a>
    </div>

    <!-- Policies Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Title</th>
                <th class="border">Type</th>
                <th class="border">Region</th>
                <th class="border">PDF Link</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($policies as $policy): ?>
                <tr>
                    <td class="border"><?= $policy['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($policy['title']) ?></td>
                    <td class="border"><?= htmlspecialchars($policy['type']) ?></td>
                    <td class="border"><?= htmlspecialchars($policy['region']) ?></td>
                    <td class="border">
                        <a href="<?= htmlspecialchars($policy['pdf_link']) ?>" target="_blank" class="text-blue-600">View PDF</a>
                    </td>
                    <td class="border">
                        <a href="?edit_policy=<?= $policy['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_policy=<?= $policy['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
