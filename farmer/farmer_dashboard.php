<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Check if user is logged in and is a farmer
if (!isLoggedIn() || getUserRole() !== 'Farmer') {
    redirectWith('/login.php', 'Please login as a farmer', 'error');
}

// Fetch farmer's products
$farmer_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE farmer_id = ? ORDER BY created_at DESC");
$stmt->execute([$farmer_id]);
$products = $stmt->fetchAll();

// Get total sales and revenue
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_sales,
        SUM(o.quantity * p.price) as total_revenue
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE p.farmer_id = ?
");
$stmt->execute([$farmer_id]);
$sales_data = $stmt->fetch();

include '../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Farmer Dashboard</h2>
        
        <a href="add_product.php" class="btn-primary">Add New Product</a>
        <a href="index.php" class="btn-primary">GOVERNAMENT POLICES</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-box"></i>
            <div class="stat-info">
                <h3>Total Products</h3>
                <p><?= count($products) ?></p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-shopping-cart"></i>
            <div class="stat-info">
                <h3>Total Sales</h3>
                <p><?= $sales_data['total_sales'] ?? 0 ?></p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-rupee-sign"></i>
            <div class="stat-info">
                <h3>Total Revenue</h3>
                <p>₹<?= number_format($sales_data['total_revenue'] ?? 0, 2) ?></p>
            </div>
        </div>
    </div>
   
    <div class="products-section">
        <h3>Your Products</h3>
        <?php if (empty($products)): ?>
            <p class="no-products">You haven't added any products yet. <a href="add_product.php">Add your first product</a></p>
        <?php else: ?>
            <div class="products-table-wrapper">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>"
                                         class="product-thumbnail">
                                </td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                                <td>₹<?= number_format($product['price'], 2) ?>/<?= $product['unit'] ?></td>
                                <td><?= $product['total_stock'] ?></td>
                                <td class="actions">
                                    <button class="btn-edit" onclick="editProduct(<?= $product['id'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteProduct(<?= $product['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-card i {
    font-size: 24px;
    color: #4CAF50;
}

.stat-info h3 {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.stat-info p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.products-table-wrapper {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th,
.products-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.products-table th {
    background: #f5f5f5;
    font-weight: bold;
}

.product-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn-edit,
.btn-delete {
    padding: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-edit {
    background: #2196F3;
    color: white;
}

.btn-delete {
    background: #f44336;
    color: white;
}

.btn-edit:hover {
    background: #1976D2;
}

.btn-delete:hover {
    background: #D32F2F;
}

.no-products {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
function editProduct(productId) {
    window.location.href = `edit_product.php?id=${productId}`;
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error deleting product');
            }
        })
        .catch(error => {
            alert('Error deleting product');
        });
    }
}
</script>

<?php include '../includes/footer.php'; ?>
