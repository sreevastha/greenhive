<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in and is a customer
if (!isLoggedIn() || getUserRole() !== 'Customer') {
    redirectWith('/login.php', 'Please login to view cart', 'error');
}

// Fetch cart items
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.unit, p.image_url, p.total_stock, 
           (p.price * c.quantity) as subtotal
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['subtotal'];
}

include 'includes/header.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
<div class="cart-container">
    <h2>Shopping Cart</h2>
    <?= showMessage() ?>

    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <p>Your cart is empty</p>
            <a href="products.php" class="btn-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-grid">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item" data-id="<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             class="item-image">
                        
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="price">₹<?= number_format($item['price'], 2) ?> per <?= $item['unit'] ?></p>
                        </div>

                        <div class="quantity-controls">
                            <button class="qty-btn minus" onclick="updateQuantity(<?= $item['id'] ?>, 'decrease')">-</button>
                            <input type="number" value="<?= $item['quantity'] ?>" 
                                   min="1" max="<?= $item['total_stock'] ?>"
                                   class="quantity-input"
                                   onchange="updateQuantity(<?= $item['id'] ?>, 'set', this.value)">
                            <button class="qty-btn plus" onclick="updateQuantity(<?= $item['id'] ?>, 'increase')">+</button>
                        </div>

                        <div class="item-subtotal">
                            ₹<?= number_format($item['subtotal'], 2) ?>
                        </div>

                        <button class="remove-item" onclick="removeItem(<?= $item['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">₹<?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Delivery Fee</span>
                    <span>₹50.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="cart-total">₹<?= number_format($total + 50, 2) ?></span>
                </div>
                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.cart-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.empty-cart {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.empty-cart i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 20px;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
}

.cart-items {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
}

.cart-item {
    display: grid;
    grid-template-columns: auto 1fr auto auto auto;
    gap: 20px;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
}

.quantity-input {
    width: 50px;
    text-align: center;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.cart-summary {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    position: sticky;
    top: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.summary-row.total {
    font-weight: bold;
    font-size: 1.2em;
    border-bottom: none;
}

.btn-checkout {
    display: block;
    width: 100%;
    padding: 15px;
    background: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
    transition: background 0.3s ease;
}

.btn-checkout:hover {
    background: #45a049;
}

@media (max-width: 768px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .item-image {
        margin: 0 auto;
    }
}
</style>

<script>
function updateQuantity(cartId, action, value = null) {
    let url = 'ajax/update_cart.php';
    let data = {
        cart_id: cartId,
        action: action
    };

    if (value !== null) {
        data.value = value;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the cart UI
            updateCartUI(data);

        } else {
             window.location.href = 'cart.php?message=' + (data.message || 'Error updating cart') + '&type=error';
             location.reload(); // Reload to show flash message
        }
    })
    .catch(error => {
        console.error('Error:', error);
         window.location.href = 'cart.php?message=Error updating cart&type=error';
        location.reload(); // Reload to show flash message
    });
}

function removeItem(cartId) {
    if (confirm('Are you sure you want to remove this item?')) {
        fetch('ajax/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart_id: cartId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                 window.location.href = 'cart.php?message=' + (data.message || 'Error removing item') + '&type=error';
                location.reload(); // Reload to show flash message
            }
        })
        .catch(error => {
            console.error('Error:', error);
             window.location.href = 'cart.php?message=Error removing item&type=error';
            location.reload(); // Reload to show flash message
        });
    }
}

function updateCartUI(data) {
    // Update quantity
    const cartItem = document.querySelector(`.cart-item[data-id="${data.cart_id}"]`);
    if (cartItem) {
        cartItem.querySelector('.quantity-input').value = data.quantity;
        cartItem.querySelector('.item-subtotal').textContent = `₹${data.subtotal.toFixed(2)}`;
    }

    // Update summary
    const subtotalElement = document.getElementById('cart-subtotal');
    const totalElement = document.getElementById('cart-total');

    subtotalElement.textContent = `₹${data.cart_subtotal.toFixed(2)}`;
    totalElement.textContent = `₹${(data.cart_subtotal + 50).toFixed(2)}`;
}
</script>

<?php include 'includes/footer.php'; ?>