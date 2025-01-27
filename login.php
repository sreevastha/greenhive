<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirectWith('/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validateInput($_POST['email']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            switch($user['role']) {
                case 'Admin':
                    redirectWith('/admin/admin_dashboard.php', 'Welcome Admin!');
                    break;
                case 'Farmer':
                    redirectWith('/farmer/farmer_dashboard.php', 'Welcome Farmer!');
                    break;
                default:
                    redirectWith('/index.php', 'Welcome back!');
            }
        } else {
            $error = "Invalid email or password";
        }
    } catch(PDOException $e) {
        $error = "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenHive</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Login to GreenHive</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Login</button>
            </form>

            <p class="auth-links">
                Don't have an account? <a href="signup.php">Sign Up</a>
            </p>
        </div>
    </div>
</body>
</html>