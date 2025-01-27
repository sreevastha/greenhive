<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isLoggedIn() || getUserRole() !== 'Admin') {
    redirectWith('/login.php', 'You do not have permission to access this page.', 'error');
}

include __DIR__ . '/../includes/header.php'; // Include header

// Handle product creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $farmer_id = $_POST['farmer_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $unit = $_POST['unit'];
    $total_stock = $_POST['total_stock'];
    $image_url = $_POST['image_url'];

    $stmt = $pdo->prepare("INSERT INTO products (farmer_id, name, category, price, unit, total_stock, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$farmer_id, $name, $category, $price, $unit, $total_stock, $image_url]);
    redirectWith('/admin/manage_products.php', 'Product added successfully!', 'success');
}

// Handle product deletion
if (isset($_GET['delete_product'])) {
    $productId = $_GET['delete_product'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    redirectWith('/admin/manage_products.php', 'Product deleted successfully!', 'success');
}

// Handle product editing
if (isset($_GET['edit_product'])) {
    $productId = $_GET['edit_product'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $productToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process the update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $productId = $_POST['product_id']; // Get the product ID from the hidden input
    $farmer_id = $_POST['farmer_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $unit = $_POST['unit'];
    $total_stock = $_POST['total_stock'];
    $image_url = $_POST['image_url'];

    $stmt = $pdo->prepare("UPDATE products SET farmer_id = ?, name = ?, category = ?, price = ?, unit = ?, total_stock = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$farmer_id, $name, $category, $price, $unit, $total_stock, $image_url, $productId]);
    redirectWith('/admin/manage_products.php', 'Product updated successfully!', 'success');
}

// Fetch products for displaying
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-8">Manage Products</h1>

    <!-- Add/Edit Product Form -->
    <form action="" method="POST" class="mb-8">
        <h2 class="text-xl font-bold mb-4"><?= isset($productToEdit) ? 'Edit Product' : 'Add Product' ?></h2>
        
        <!-- Hidden input for product ID when editing -->
        <?php if (isset($productToEdit)): ?>
            <input type="hidden" name="product_id" value="<?= $productToEdit['id'] ?>">
        <?php endif; ?>

        <input type="text" name="name" placeholder="Product Name" required class="border p-2 mb-2 w-full" value="<?= isset($productToEdit) ? htmlspecialchars($productToEdit['name']) : '' ?>">
        <input type="number" name="price" placeholder="Price" required class="border p-2 mb-2 w-full" value="<?= isset($productToEdit) ? htmlspecialchars($productToEdit['price']) : '' ?>">
        <input type="text" name="image_url" placeholder="Image URL" required class="border p-2 mb-2 w-full" value="<?= isset($productToEdit) ? htmlspecialchars($productToEdit['image_url']) : '' ?>">
        <input type="number" name="total_stock" placeholder="Total Stock" required class="border p-2 mb-2 w-full" value="<?= isset($productToEdit) ? htmlspecialchars($productToEdit['total_stock']) : '' ?>">
        
        <select name="category" class="border p-2 mb-2 w-full">
            <option value="Vegetables" <?= (isset($productToEdit) && $productToEdit['category'] == 'Vegetables') ? 'selected' : '' ?>>Vegetables</option>
            <option value="Fruits" <?= (isset($productToEdit) && $productToEdit['category'] == 'Fruits') ? 'selected' : '' ?>>Fruits</option>
            <option value="Grains" <?= (isset($productToEdit) && $productToEdit['category'] == 'Grains') ? 'selected' : '' ?>>Grains</option>
            <option value="Leafy Veg" <?= (isset($productToEdit) && $productToEdit['category'] == 'Leafy Veg') ? 'selected' : '' ?>>Leafy Veg</option>
        </select>
        
        <select name="unit" class="border p-2 mb-2 w-full">
            <option value="kg" <?= (isset($productToEdit) && $productToEdit['unit'] == 'kg') ? 'selected' : '' ?>>kg</option>
            <option value="piece" <?= (isset($productToEdit) && $productToEdit['unit'] == 'piece') ? 'selected' : '' ?>>Piece</option>
        </select>
        
        <input type="number" name="farmer_id" placeholder="Farmer ID" required class="border p-2 mb-2 w-full" value="<?= isset($productToEdit) ? htmlspecialchars($productToEdit['farmer_id']) : '' ?>">
        
        <button type="submit" name="<?= isset($productToEdit) ? 'update_product' : 'add_product' ?>" class="bg-blue-500 text-white p-2 rounded"><?= isset($productToEdit) ? 'Update Product' : 'Add Product' ?></button>
    </form>

    <!-- Products Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Name</th>
                <th class="border">Category</th>
                <th class="border">Price</th>
                <th class="border">Total Stock</th>
                <th class="border">Farmer ID</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="border"><?= $product['id'] ?></td>
                    <td class="border"><?= htmlspecialchars($product['name']) ?></td>
                    <td class="border"><?= htmlspecialchars($product['category']) ?></td>
                    <td class="border"><?= htmlspecialchars($product['price']) ?></td>
                    <td class="border"><?= htmlspecialchars($product['total_stock']) ?></td>
                    <td class="border"><?= htmlspecialchars($product['farmer_id']) ?></td>
                    <td class="border">
                        <a href="?edit_product=<?= $product['id'] ?>" class="text-blue-600">Edit</a>
                        <a href="?delete_product=<?= $product['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>