<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle weekly menu item creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_menu_item'])) {
    $day = $_POST['day'];
    $meal_type = $_POST['meal_type'];
    $category = $_POST['category'];
    $menu = $_POST['menu'];
    $imgurl = $_POST['imgurl'];

    $stmt = $pdo->prepare("INSERT INTO weekly_menu (day, meal_type, category, menu, imgurl) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$day, $meal_type, $category, $menu, $imgurl]);
    redirectWith('/admin/manage_weekly_menu.php', 'Menu item added successfully!', 'success');
}

// Handle weekly menu item deletion
if (isset($_GET['delete_menu_item'])) {
    $menuItemId = $_GET['delete_menu_item'];
    $stmt = $pdo->prepare("DELETE FROM weekly_menu WHERE id = ?");
    $stmt->execute([$menuItemId]);
    redirectWith('/admin/manage_weekly_menu.php', 'Menu item deleted successfully!', 'success');
}

// Handle weekly menu item editing
if (isset($_GET['edit_menu_item'])) {
    $menuItemId = $_GET['edit_menu_item'];
    $stmt = $pdo->prepare("SELECT * FROM weekly_menu WHERE id = ?");
    $stmt->execute([$menuItemId]);
    $menuItemToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_menu_item'])) {
    $menuItemId = $_POST['menu_item_id']; // Get the menu item ID from the hidden input
    $day = $_POST['day'];
    $meal_type = $_POST['meal_type'];
    $category = $_POST['category'];
    $menu = $_POST['menu'];
    $imgurl = $_POST['imgurl'];

    $stmt = $pdo->prepare("UPDATE weekly_menu SET day = ?, meal_type = ?, category = ?, menu = ?, imgurl = ? WHERE id = ?");
    $stmt->execute([$day, $meal_type, $category, $menu, $imgurl, $menuItemId]);
    redirectWith('/admin/manage_weekly_menu.php', 'Menu item updated successfully!', 'success');
}

// Fetch weekly menu items for displaying
$stmt = $pdo->query("SELECT * FROM weekly_menu");
$weeklyMenuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Weekly Menu</h1>

    <!-- Add/Edit Weekly Menu Item Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($menuItemToEdit) ? 'Edit Menu Item' : 'Add Menu Item' ?></h2>
        
        <!-- Hidden input for menu item ID when editing -->
        <?php if (isset($menuItemToEdit)): ?>
            <input type="hidden" name="menu_item_id" value="<?= $menuItemToEdit['id'] ?>">
        <?php endif; ?>

        <select name="day" class="border p-2 mb-2 w-full" required>
            <option value="Monday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Monday') ? 'selected' : '' ?>>Monday</option>
            <option value="Tuesday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Tuesday') ? 'selected' : '' ?>>Tuesday</option>
            <option value="Wednesday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Wednesday') ? 'selected' : '' ?>>Wednesday</option>
            <option value="Thursday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Thursday') ? 'selected' : '' ?>>Thursday</option>
            <option value="Friday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Friday') ? 'selected' : '' ?>>Friday</option>
            <option value="Saturday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Saturday') ? 'selected' : '' ?>>Saturday</option>
            <option value="Sunday" <?= (isset($menuItemToEdit) && $menuItemToEdit['day'] == 'Sunday') ? 'selected' : '' ?>>Sunday</option>
        </select>

        <select name="meal_type" class="border p-2 mb-2 w-full" required>
            <option value="Morning" <?= (isset($menuItemToEdit) && $menuItemToEdit['meal_type'] == 'Morning') ? 'selected' : '' ?>>Morning</option>
            <option value="Afternoon" <?= (isset($menuItemToEdit) && $menuItemToEdit['meal_type'] == 'Afternoon') ? 'selected' : '' ?>>Afternoon</option>
            <option value="Night" <?= (isset($menuItemToEdit) && $menuItemToEdit['meal_type'] == 'Night') ? 'selected' : '' ?>>Night</option>
        </select>

        <select name="category" class="border p-2 mb-2 w-full" required>
            <option value="BP" <?= (isset($menuItemToEdit) && $menuItemToEdit['category'] == 'BP') ? 'selected' : '' ?>>BP</option>
            <option value="Diabetes" <?= (isset($menuItemToEdit) && $menuItemToEdit['category'] == 'Diabetes') ? 'selected' : '' ?>>Diabetes</option>
            <option value="Thyroid" <?= (isset($menuItemToEdit) && $menuItemToEdit['category'] == 'Thyroid') ? 'selected' : '' ?>>Thyroid</option>
            <option value="Weight Gain" <?= (isset($menuItemToEdit) && $menuItemToEdit['category'] == 'Weight Gain') ? 'selected' : '' ?>>Weight Gain</option>
            <option value="Weight Loss" <?= (isset($menuItemToEdit) && $menuItemToEdit['category'] == 'Weight Loss') ? 'selected' : '' ?>>Weight Loss</option>
        </select>

        <textarea name="menu" placeholder="Menu" required class="border p-2 mb-2 w-full"><?= isset($menuItemToEdit) ? htmlspecialchars($menuItemToEdit['menu']) : '' ?></textarea>
        
        <input type="text" name="imgurl" placeholder="Image URL" class="border p-2 mb-2 w-full" value="<?= isset($menuItemToEdit) ? htmlspecialchars($menuItemToEdit['imgurl']) : '' ?>">

        <button type="submit" name="<?= isset($menuItemToEdit) ? 'update_menu_item' : 'add_menu_item' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($menuItemToEdit) ? 'Update Menu Item' : 'Add Menu Item' ?></button>
    </form>

    <!-- Weekly Menu Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Day</th>
                <th class="border">Meal Type</th>
                <th class="border">Category</th>
                <th class="border">Menu</th>
                <th class="border">Image URL</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeklyMenuItems as $menuItem): ?>
                <tr>
                    <td class="border"><?= $menuItem['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($menuItem['day']) ?></td>
                    <td class="border"><?= htmlspecialchars($menuItem['meal_type']) ?></td>
                    <td class="border"><?= htmlspecialchars($menuItem['category']) ?></td>
                    <td class="border"><?= htmlspecialchars($menuItem['menu']) ?></td>
                    <td class="border"><?= htmlspecialchars($menuItem['imgurl']) ?></td>
                    <td class="border">
                        <a href="?edit_menu_item=<?= $menuItem['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_menu_item=<?= $menuItem['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>