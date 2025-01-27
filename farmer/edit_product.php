<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Check if user is logged in and is a farmer
if (!isLoggedIn() || getUserRole() !== 'Farmer') {
    redirectWith('/login.php', 'Please login as a farmer', 'error');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$farmer_id = $_SESSION['user_id'];

// Fetch product details
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND farmer_id = ?");
    $stmt->execute([$product_id, $farmer_id]);
    $product = $stmt->fetch();

    if (!$product) {
        redirectWith('/farmer/farmer_dashboard.php', 'Product not found', 'error');
    }
} catch(PDOException $e) {
    redirectWith('/farmer/farmer_dashboard.php', 'Error fetching product details', 'error');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = validateInput($_POST['name']);
    $category = validateInput($_POST['category']);
    $price = floatval($_POST['price']);
    $unit = validateInput($_POST['unit']);
    $stock = intval($_POST['stock']);
    $image_url = validateInput($_POST['image_url']);

    try {
        $stmt = $pdo->prepare("UPDATE products 
                              SET name = ?, category = ?, price = ?, 
                                  unit = ?, total_stock = ?, image_url = ? 
                              WHERE id = ? AND farmer_id = ?");
        $stmt->execute([$name, $category, $price, $unit, $stock, $image_url, $product_id, $farmer_id]);
        redirectWith('/farmer/farmer_dashboard.php', 'Product updated successfully!', 'success');
    } catch(PDOException $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="edit-product-container">
    <h2>Edit Product</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="edit-product-form">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="Vegetables" <?= $product['category'] === 'Vegetables' ? 'selected' : '' ?>>Vegetables</option>
                <option value="Fruits" <?= $product['category'] === 'Fruits' ? 'selected' : '' ?>>Fruits</option>
                <option value="Grains" <?= $product['category'] === 'Grains' ? 'selected' : '' ?>>Grains</option>
                <option value="Leafy Veg" <?= $product['category'] === 'Leafy Veg' ? 'selected' : '' ?>>Leafy Vegetables</option>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required>
        </div>

        <div class="form-group">
            <label for="unit">Unit</label>
            <select id="unit" name="unit" required>
                <option value="kg" <?= $product['unit'] === 'kg' ? 'selected' : '' ?>>Per Kg</option>
                <option value="piece" <?= $product['unit'] === 'piece' ? 'selected' : '' ?>>Per Piece</option>
            </select>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?= $product['total_stock'] ?>" required>
        </div>

        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>" required>
            <div class="image-preview" id="imagePreview">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product image">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Product</button>
            <a href="farmer_dashboard.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

.add-product-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.add-product-form {
    display: grid;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
}

.image-preview {
    width: 200px;
    height: 200px;
    border: 2px dashed #ddd;
    border-radius: 4px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f9f9;
    overflow: hidden;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

small {
    color: #666;
    font-size: 14px;
}

.btn-primary {
    background: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #45a049;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-error {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

@media (max-width: 768px) {
    .add-product-container {
        margin: 10px;
        padding: 15px;
    }
}
</style>

<script>
document.getElementById('image_url').addEventListener('input', function() {
    const imageUrl = this.value;
    const previewDiv = document.getElementById('imagePreview');
    
    if (imageUrl) {
        const img = document.createElement('img');
        img.src = imageUrl;
        img.onerror = function() {
            previewDiv.innerHTML = '<p>Invalid image URL</p>';
            this.classList.add('error');
        };
        img.onload = function() {
            previewDiv.innerHTML = '';
            previewDiv.appendChild(img);
            this.classList.remove('error');
        };
    } else {
        previewDiv.innerHTML = '';
    }
});

// Form validation
document.querySelector('.add-product-form').addEventListener('submit', function(e) {
    const imageUrl = document.getElementById('image_url');
    const img = new Image();
    
    img.onerror = function() {
        e.preventDefault();
        alert('Please enter a valid image URL');
        imageUrl.focus();
    };
    
    img.src = imageUrl.value;
});
</script>

<?php include '../includes/footer.php'; ?>