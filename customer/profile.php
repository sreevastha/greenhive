<?php
require_once __DIR__ . '/../includes/config.php'; // Corrected path
require_once __DIR__ . '/../includes/db_connect.php'; // Corrected path
require_once __DIR__ . '/../includes/functions.php'; // Corrected path

// Check if the user is logged in
if (!isLoggedIn()) {
    redirectWith('/login.php', 'Please login to view your profile.', 'error');
}

$user_id = $_SESSION['user_id'];

// Fetch the user's profile details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $phone_number = validateInput($_POST['phone_number']);
    $age = validateInput($_POST['age']);
    $gender = validateInput($_POST['gender']);

    // Update the user's profile in the database
    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone_number = ?, age = ?, gender = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $phone_number, $age, $gender, $user_id]);
        redirectWith('/profile.php', 'Profile updated successfully!', 'success');
    } catch (PDOException $e) {
        redirectWith('/profile.php', 'Error updating profile. Please try again.', 'error');
    }
}

include __DIR__ . '/../includes/header.php'; // Corrected path
?>

<!-- Profile Section -->
<div class="container mx-auto px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-center mb-8">Your Profile</h2>
    <div class="max-w-2xl mx-auto">
        <form action="" method="POST" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" 
                       class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" 
                       class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" 
                       class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <!-- Age -->
            <div>
                <label for="age" class="block text-gray-700 font-medium mb-2">Age</label>
                <input type="number" name="age" id="age" value="<?= htmlspecialchars($user['age']) ?>" 
                       class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <!-- Gender -->
            <div>
                <label for="gender" class="block text-gray-700 font-medium mb-2">Gender</label>
                <select name="gender" id="gender" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; // Corrected path ?>