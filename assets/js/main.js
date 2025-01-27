// Wait for DOM to load
document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
  const navLinks = document.querySelector(".nav-links");

  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", function () {
      navLinks.classList.toggle("active");
    });
  }

  // Add to Cart functionality
  const addToCartButtons = document.querySelectorAll(".add-to-cart");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const productId = this.getAttribute("data-product-id");
      addToCart(productId);
    });
  });

  // Cart functionality
  function addToCart(productId) {
    fetch(`${SITE_URL}/ajax/add_to_cart.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Product added to cart!", "success");
          updateCartCount(data.cartCount);
        } else {
          showNotification(data.message || "Error adding to cart", "error");
        }
      })
      .catch((error) => {
        showNotification("Error adding to cart", "error");
      });
  }

  // Notification system
  function showNotification(message, type = "success") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  // Update cart count in navigation
  function updateCartCount(count) {
    const cartCount = document.querySelector(".cart-count");
    if (cartCount) {
      cartCount.textContent = count;
    }
  }

  // Product image error handling
  const productImages = document.querySelectorAll(".product-image");
  productImages.forEach((img) => {
    img.onerror = function () {
      this.src = `${SITE_URL}/assets/images/placeholder.jpg`;
    };
  });

  // Search functionality
  const searchInput = document.querySelector("#searchInput");
  if (searchInput) {
    let timeout = null;
    searchInput.addEventListener("input", function () {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        filterProducts(this.value);
      }, 500);
    });
  }

  // Filter products
  function filterProducts(query) {
    const productCards = document.querySelectorAll(".product-card");
    productCards.forEach((card) => {
      const productName = card.querySelector("h3").textContent.toLowerCase();
      const productFarmer = card
        .querySelector(".farmer")
        .textContent.toLowerCase();

      if (
        productName.includes(query.toLowerCase()) ||
        productFarmer.includes(query.toLowerCase())
      ) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }

  // Price range slider
  const priceRange = document.querySelector("#priceRange");
  const priceOutput = document.querySelector("#priceOutput");
  if (priceRange && priceOutput) {
    priceRange.addEventListener("input", function () {
      priceOutput.textContent = `₹${this.value}`;
      filterByPrice(this.value);
    });
  }

  // Category filter
  const categoryFilters = document.querySelectorAll(".category-filter");
  categoryFilters.forEach((filter) => {
    filter.addEventListener("change", function () {
      filterByCategory();
    });
  });

  // Sort products
  const sortSelect = document.querySelector("#sortProducts");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      sortProducts(this.value);
    });
  }

  // Form validation
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      if (!validateForm(this)) {
        e.preventDefault();
      }
    });
  });

  function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll("input[required], select[required]");

    inputs.forEach((input) => {
      if (!input.value.trim()) {
        isValid = false;
        input.classList.add("error");
      } else {
        input.classList.remove("error");
      }
    });

    return isValid;
  }

  // Image URL validator
  const imageUrlInputs = document.querySelectorAll('input[type="url"]');
  imageUrlInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      validateImageUrl(this.value, this);
    });
  });

  function validateImageUrl(url, input) {
    const img = new Image();
    img.onload = function () {
      input.classList.remove("error");
      input.classList.add("valid");
    };
    img.onerror = function () {
      input.classList.add("error");
      input.classList.remove("valid");
      showNotification("Please enter a valid image URL", "error");
    };
    img.src = url;
  }

  // Quantity input handlers
  const quantityInputs = document.querySelectorAll(".quantity-input");
  quantityInputs.forEach((input) => {
    input.addEventListener("change", function () {
      updateQuantity(this);
    });
  });

  function updateQuantity(input) {
    const productId = input.getAttribute("data-product-id");
    const quantity = input.value;

    fetch(`${SITE_URL}/ajax/update_quantity.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}&quantity=${quantity}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          updateCartTotal(data.total);
        } else {
          showNotification(data.message || "Error updating quantity", "error");
        }
      });
  }
});

// Add CSS for notifications
const style = document.createElement("style");
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        z-index: 1000;
        animation: slideIn 0.5s ease-out;
    }

    .notification.success {
        background-color: #4CAF50;
    }

    .notification.error {
        background-color: #f44336;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
