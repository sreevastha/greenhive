<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Fetch categories for filter
$categories = ['Vegetables', 'Fruits', 'Grains', 'Leafy Veg'];

// Build query based on filters
$query = "SELECT p.*, u.name as farmer_name 
          FROM products p 
          JOIN users u ON p.farmer_id = u.id 
          WHERE p.total_stock > 0";

// Apply category filter if set
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = validateInput($_GET['category']);
    $query .= " AND p.category = '$category'";
}

// Apply sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
switch ($sort) {
    case 'price_low':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY p.price DESC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
}

$products = $pdo->query($query)->fetchAll();

include 'includes/header.php';
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Custom Cursor */
        * {
            cursor: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjNGNBZjUwIiBkPSJNMCAwaDI0djI0SDBWMHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMjAgN3YtM2MwLTIuMjEtMS43OS00LTYtNnMtNiAzLjc5LTYgNnYzSDM2djE4aDR2LTRoNHY0aDR2LTRoNHY0aDR2LTRoNHY0aDR2LTRIMjB6TTEyIDEydjRoLTR2LTRoNHoiLz48L3N2Zz4='), auto;
        }

        /* Search Bar Animation */
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.5);
        }

        /* Product Card Hover Effect */
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<div class="container mx-auto px-4 py-8">
    <?= showMessage() ?>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-md p-6 w-full md:w-1/4">
            <h3 class="text-lg font-semibold mb-4">Filters</h3>
            <form action="" method="GET" class="filters-form">
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="category"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category ?>"
                            <?= isset($_GET['category']) && $_GET['category'] === $category ? 'selected' : '' ?>>
                            <?= $category ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                    <select id="sort" name="sort" onchange="this.form.submit()"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to
                            High</option>
                        <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to
                            Low</option>
                    </select>
                </div>
            </form>
        </div>


        <!-- Products Section -->
        <div class="w-full md:w-3/4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">Available Products</h2>
                <div class="search-bar relative">
                    <input type="text" id="searchInput" placeholder="Search products..."
                        class="shadow appearance-none border rounded w-full md:w-64 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <svg class="absolute right-0 top-0 mt-3 mr-4 h-5 w-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <?php foreach ($products as $product): ?>
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-2">
                    <a href="product_details.php?id=<?= $product['id'] ?>"> <img
                            src="<?= htmlspecialchars($product['image_url']) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                        <p class="text-gray-600 italic mb-2">By
                            <?= htmlspecialchars($product['farmer_name']) ?>
                        </p>
                        <p class="text-green-500 font-medium mb-2">
                            <?= htmlspecialchars($product['category']) ?>
                        </p>
                        <p class="text-gray-700 font-bold mb-2">₹
                        <?= number_format($product['price'], 2) ?> per <?= $product['unit'] ?>
                        </p>
                        <p class="text-gray-500 mb-2">In Stock: <?= $product['total_stock'] ?></p>

                        <?php if (isLoggedIn() && getUserRole() === 'Customer'): ?>
                        <div class="flex items-center mt-4">
                            <input type="number" min="1" max="<?= $product['total_stock'] ?>" value="1"
                                class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline quantity-input"
                                id="qty_<?= $product['id'] ?>">
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2 add-to-cart"
                                data-product-id="<?= $product['id'] ?>">
                                Add to Cart
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        const cartCountElement = document.getElementById('cart-count'); // Get the cart count element

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                const productId = this.dataset.productId;
                const quantityInput = document.getElementById(`qty_${productId}`);
                const quantity = parseInt(quantityInput.value);

                if (isNaN(quantity) || quantity <= 0) {
                    // Use a flash message for validation errors
                    window.location.href = 'products.php?message=Please enter a valid quantity&type=error';
                    return;
                }

                // Send AJAX request to add item to cart
                fetch('ajax/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            cartCountElement.textContent = `(${data.cart_count})`; // Update cart count
                            // Show a success message (you can customize this)
                            alert('Item added to cart!'); 
                        } else {
                            // Use a flash message for errors
                            window.location.href = 'products.php?message=Error adding item to cart.&type=error';
                        }
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Use a flash message for errors
                        window.location.href = 'products.php?message=Error adding item to cart.&type=error';
                        window.location.reload();
                    });
            });
        });

        // Function to fetch and display cart count on page load.
        function fetchCartCount() {
            fetch('ajax/get_cart_count.php') // Assuming this file exists
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartCountElement.textContent = `(${data.cart_count})`;
                    } else {
                        console.error('Error fetching cart count:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart count:', error);
                });
        }

        // Call fetchCartCount when the page loads to initialize the cart count
        fetchCartCount();

        // Live Search Functionality
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.product-card');

        searchInput.addEventListener('keyup', function(event) {
            const searchTerm = event.target.value.toLowerCase();

            productCards.forEach(card => {
                const productName = card.querySelector('h3').textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>