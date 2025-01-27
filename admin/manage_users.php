<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone_number'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone_number, age, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $phone_number, $age, $gender, $role]);
    redirectWith('/admin/manage_users.php', 'User added successfully!', 'success');
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    redirectWith('/admin/manage_users.php', 'User deleted successfully!', 'success');
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Users</h1>

    <!-- Add User Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4">Add User</h2>
        <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full">
        <input type="email" name="email" placeholder="Email" required class="border p-2 mb-2 w-full">
        <input type="password" name="password" placeholder="Password" required class="border p-2 mb-2 w-full">
        <input type="text" name="phone_number" placeholder="Phone Number" class="border p-2 mb-2 w-full">
        <input type="number" name="age" placeholder="Age" class="border p-2 mb-2 w-full">
        <select name="gender" class="border p-2 mb-2 w-full">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <select name="role" class="border p-2 mb-2 w-full">
            <option value="Customer">Customer</option>
            <option value="Farmer">Farmer</option>
            <option value="Admin">Admin</option>
        </select>
        <button type="submit" name="add_user" class="bg-blue-500 text-white p-2 rounded">Add User</button>
    </form>

    <!-- Users Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Name</th>
                <th class="border">Email</th>
                <th class="border">Role</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="border"><?= $user['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($user['name']) ?></td>
                    <td class="border"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="border"><?= htmlspecialchars($user['role']) ?></td>
                    <td class="border">
                        <a href="?edit_user=<?= $user['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_user=<?= $user['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>