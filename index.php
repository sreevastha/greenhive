<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

include 'includes/header.php';
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1606787366850-de6330128bfc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.7;
            z-index: -1;
        }

        .hover-underline-animation:hover {
            text-decoration: underline;
            text-decoration-color: #4CAF50;
            text-decoration-thickness: 3px;
            text-underline-offset: 5px;
            transition: text-decoration 0.3s ease;
        }

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

<!-- Hero Section -->
<section class="hero relative bg-gray-900 pt-24 pb-24 md:pb-48 text-white">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-8xl font-bold mb-4 md:mb-6 leading-tight">
            Welcome to <span class="text-green-500">GreenHive</span>
        </h1>
        <p class="text-lg md:text-xl lg:text-2xl mb-6 md:mb-10">
            Connecting urban consumers with fresh, organic produce and health-focused meals
        </p>
         <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4">
            <a href="products.php"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 md:px-8 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:scale-105 block">Browse
                Products</a>
            <a href="meals.php"
                class="bg-transparent border border-white text-white px-6 py-3 md:px-8 rounded-lg font-semibold hover:bg-white hover:text-green-500 transition duration-300 ease-in-out transform hover:scale-105 block">View
                Meal Plans</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="bg-white py-12 md:py-16">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
            <div>
                <img src="https://media-hosting.imagekit.io//2d0081757f0e4c65/screenshot_1737804086479.png?Expires=1832412090&Key-Pair-Id=K2ZIVPTIP2VGHC&Signature=ZMq0HSNTMYNq7tUdqnrRgiruIqc44LL~PB081C4zXemtQnc7jUmn8145IwdIiLYcyvpGNKpQTb3pKH1g4197dhsWLJdNYNjhsuleGSLNW8ruqH5mgAMNVmL-Fi1U~dwzPyiCPc~6XclSJYmSAmf4UapSWles9OwnxBRgc9O14dpZNnVbcLxbtnXFUVhZnAkTd4Lxa3DRxoVgk5Ha6DO3hGeExqa7StpCEtf6ZPh6~LruxhRyf-bAw2cHDWy~KxVukzTcgcTV24ddOzvieyDNvmFl53Pye3pBS2pb34YgW0q7jnInmeElqUbRYo6SpQtqJx8EaDzUKoj6Eut1SfHaZg__"
                    alt="About GreenHive" class="rounded-lg shadow-lg transform hover:scale-105 transition duration-300 w-full">
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">About GreenHive</h2>
                <p class="text-gray-700 mb-3 md:mb-4">
                    GreenHive is more than just a grocery delivery service. We're on a mission to revolutionize the
                    way people connect with their food.
                </p>
                <p class="text-gray-700">
                    By partnering directly with local farmers and empowering rural women, we're creating a sustainable
                    food system that's good for people and the planet.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-gray-100 py-12 md:py-16">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-8 md:mb-12">What We Offer</h2>
         <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Fresh Produce -->
            <div
                class="bg-white rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                <img src="https://images.unsplash.com/photo-1597362925123-77861d3fbac7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                    alt="Fresh Produce" class="w-full h-40 md:h-48 object-cover">
                <div class="p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold mb-2 hover-underline-animation">Fresh, Organic Produce</h3>
                    <p class="text-gray-700 text-sm md:text-base">
                        Delivered straight from local farms to your doorstep, ensuring peak freshness and flavor.
                    </p>
                </div>
            </div>

            <!-- Healthy Meals -->
            <div
                class="bg-white rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                <img src="https://images.unsplash.com/photo-1543352634-a1c51d9f1fa7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                    alt="Healthy Meals" class="w-full h-40 md:h-48 object-cover">
                <div class="p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold mb-2 hover-underline-animation">Health-Focused Meal Plans</h3>
                    <p class="text-gray-700 text-sm md:text-base">
                        Nutritious and delicious meals cooked with fresh, wholesome ingredients, tailored to your dietary
                        needs.
                    </p>
                </div>
            </div>

            <!-- Composting Program -->
            <div
                class="bg-white rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                <img src="https://images.unsplash.com/photo-1596464716127-f2a82984de30?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                    alt="Composting"  class="w-full h-40 md:h-48 object-cover">
                <div class="p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold mb-2 hover-underline-animation">Sustainable Composting</h3>
                     <p class="text-gray-700 text-sm md:text-base">
                    Reduce your environmental impact with our convenient composting program. We collect your food
                    waste and turn it into nutrient-rich soil.
                </p>
            </div>
            </div>

        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="bg-green-500 py-12 md:py-16">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 text-center text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Our Impact</h2>
        <p class="text-lg mb-8 md:mb-12">
            We're committed to creating a positive impact on our community and the environment.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <div>
                <h4 class="text-lg md:text-xl font-bold mb-2">Supporting Local Farmers</h4>
                <p class="text-gray-200 text-sm md:text-base">
                    We source our produce directly from local farmers, supporting their livelihoods and promoting
                    sustainable agriculture.
                </p>
            </div>
            <div>
                <h4 class="text-lg md:text-xl font-bold mb-2">Empowering Women</h4>
                <p class="text-gray-200 text-sm md:text-base">
                    Our meal preparation program provides employment opportunities for rural women, empowering them
                    economically.
                </p>
            </div>
            <div>
                <h4 class="text-lg md:text-xl font-bold mb-2">Reducing Food Waste</h4>
                <p class="text-gray-200 text-sm md:text-base">
                    Our composting program helps reduce food waste, minimizing our environmental footprint and closing
                    the loop on food production.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="bg-white py-12 md:py-16">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Join the GreenHive Movement</h2>
        <p class="text-gray-700 mb-6 md:mb-8">
            Experience the difference of fresh, healthy, and sustainable food.
        </p>
        <a href="signup.php"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 md:px-8 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:scale-105">Sign
            Up Now</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>