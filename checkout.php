<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    redirectWith('/login.php', 'Please login to checkout.', 'error');
}

// Get user details
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch cart items
$stmt = $pdo->prepare("
    SELECT 
        c.id AS cart_id, 
        c.quantity, 
        p.id AS product_id,
        p.name AS product_name, 
        p.price AS product_price,
        p.unit AS product_unit
    FROM cart AS c
    JOIN products AS p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPrice = 0;

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get address details
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    // ... other address fields

    // Get payment method
    $paymentMethod = $_POST['payment_method']; 

    // Basic validation (add more as needed)
    if (empty($address) || empty($city) || empty($pincode)) {
        redirectWith('/checkout.php', 'Please fill all address fields.', 'error');
    }

    // Begin transaction to ensure atomicity 
    try {
        $pdo->beginTransaction();

        // Loop through cart items
        foreach ($cartItems as $item) {
            // Calculate total price
            $totalPrice += $item['product_price'] * $item['quantity'];

            // Insert order into database
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, product_id, quantity, total_price, status) 
                VALUES (:userId, :productId, :quantity, :totalPrice, :status)
            ");

            $stmt->execute([
                ':userId' => $userId,
                ':productId' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':totalPrice' => $item['product_price'] * $item['quantity'], 
                ':status' => $paymentMethod === 'cod' ? 'Pending' : 'Confirmed', 
            ]);

            // Decrease product stock
            $updateStockStmt = $pdo->prepare("
                UPDATE products 
                SET total_stock = total_stock - :quantity 
                WHERE id = :productId
            ");
            $updateStockStmt->execute([
                ':quantity' => $item['quantity'],
                ':productId' => $item['product_id']
            ]);
        }

        // Clear the cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Commit transaction if all went well
        $pdo->commit();

        // Redirect to index.php with success message
        redirectWith('/index.php', 'Order placed successfully! Check your order status in your profile.', 'success');

    } catch (PDOException $e) {
        // Rollback transaction if something went wrong
        $pdo->rollback();
        // Log the error (for debugging purposes)
        error_log("Checkout error: " . $e->getMessage()); 
        // Redirect with error message
        redirectWith('/checkout.php', 'An error occurred during checkout. Please try again later.', 'error');
    }
} 

include 'includes/header.php'; // Include header after processing checkout logic 
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Checkout</h2>

    <form action="" method="POST">
        <div class="mb-4">
            <h3 class="text-lg font-medium mb-2">Shipping Address</h3>
            <input type="text" name="address" placeholder="Address" required
                value="<?= htmlspecialchars($user['address'] ?? '') ?>"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            <div class="flex space-x-4 mt-2">
                <input type="text" name="city" placeholder="City" required
                    value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                    class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                <input type="text" name="pincode" placeholder="Pincode" required
                    value="<?= htmlspecialchars($user['pincode'] ?? '') ?>"
                    class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>

        <h3 class="text-lg font-medium mb-2">Order Summary</h3>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Product</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td class="border px-4 py-2">
                        <?= htmlspecialchars($item['product_name']) ?>
                    </td>
                    <td class="border px-4 py-2">
                        <?= $item['quantity'] . ' ' . $item['product_unit'] ?>
                    </td>
                    <td class="border px-4 py-2">
                        <?= $item['product_price'] ?>
                    </td>
                    <td class="border px-4 py-2">
                        <?= $item['product_price'] * $item['quantity'] ?>
                    </td>
                </tr>
                <?php 
                    $totalPrice += ($item['product_price'] * $item['quantity']);
                endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="border px-4 py-2 text-right font-bold">Total:</td>
                    <td class="border px-4 py-2 font-bold">
                        <?= $totalPrice ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4">
            <h3 class="text-lg font-medium mb-2">Payment Method</h3>
            <div class="flex items-center mb-2">
                <input type="radio" name="payment_method" value="cod" id="cod" checked
                    class="form-radio h-4 w-4 text-green-500">
                <label for="cod" class="ml-2">Cash on Delivery</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="payment_method" value="online" id="online"
                    class="form-radio h-4 w-4 text-green-500">
                <label for="online" class="ml-2">Online Payment (Dummy)</label>
            </div>
        </div>
        <button type="submit"
            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-md mt-4 focus:outline-none focus:ring focus:ring-green-300">Pay
            Now</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>