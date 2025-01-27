<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

include 'includes/header.php';

// Fetch subscription details for the logged-in user
$subscription = null;
if (isLoggedIn() && getUserRole() === 'Customer') {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY start_date DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $subscription = $stmt->fetch();
}
?>

<!-- Hero Section -->
<div class="bg-cover bg-center py-32" style="background-image: url('https://images.unsplash.com/photo-1606787366850-de6330128bfc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
    <div class="container mx-auto px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">Meal Plans for a Healthier You</h1>
        <p class="text-lg lg:text-xl text-white mb-8">Tailored meal plans designed for your health needs, cooked with fresh, organic ingredients.</p>
        <?php if (isLoggedIn() && getUserRole() === 'Customer'): ?>
            <?php if ($subscription): ?>
                <div class="space-x-4">
                    <a href="#renew" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">Renew Subscription</a>
                </div>
            <?php else: ?>
                <a href="#subscribe" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">Subscribe Now</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">Login to Subscribe</a>
        <?php endif; ?>
    </div>
</div>

<!-- About Our Meal Plans Section -->
<section class="bg-white py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-8">About Our Meal Plans</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <h3 class="text-2xl font-bold">What We Offer</h3>
                <p class="text-gray-700">Our meal plans are designed to cater to specific health conditions, including:</p>
                <ul class="list-disc list-inside text-gray-700">
                    <li>High Blood Pressure (BP)</li>
                    <li>Diabetes</li>
                    <li>Thyroid</li>
                    <li>Weight Gain</li>
                    <li>Weight Loss</li>
                </ul>
                <p class="text-gray-700">Each meal is prepared with fresh, organic ingredients and cooked by trained rural women to ensure quality and nutrition.</p>
            </div>
            <div class="space-y-4">
                <h3 class="text-2xl font-bold">How It Works</h3>
                <ol class="list-decimal list-inside text-gray-700">
                    <li>Choose a meal plan based on your health needs.</li>
                    <li>Subscribe and provide your health details (height, weight, age, etc.).</li>
                    <li>Receive fresh, nutritious meals delivered to your doorstep.</li>
                    <li>Enjoy a healthier lifestyle with our guidance and support.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Meal Plans Section -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-8">Menu</h2>

        <?php if (isLoggedIn() && getUserRole() === 'Customer'): ?>
            <!-- Display Weekly Menu for Subscribers -->

            <?php
            $stmt = $pdo->query("SELECT * FROM weekly_menu ORDER BY category, FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), FIELD(meal_type, 'Morning', 'Afternoon', 'Night')");
            $weeklyMenu = $stmt->fetchAll();

            $groupedMenu = [];
            foreach ($weeklyMenu as $menuItem) {
                $category = $menuItem['category'];
                if (!isset($groupedMenu[$category])) {
                    $groupedMenu[$category] = [];
                }

                $day = $menuItem['day'];
                if (!isset($groupedMenu[$category][$day])) {
                    $groupedMenu[$category][$day] = [];
                }

                $groupedMenu[$category][$day][$menuItem['meal_type']] = $menuItem; // Store the entire menu item
            }
            ?>

            <?php foreach ($groupedMenu as $category => $days): ?>
                <h3 class="text-2xl font-semibold mt-6 mb-4"><?= htmlspecialchars($category) ?></h3>
                <?php foreach ($days as $day => $meals): ?>
                    <h4 class="text-xl font-medium mt-4 mb-2"><?= htmlspecialchars($day) ?></h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php
                        $mealOrder = ['Morning', 'Afternoon', 'Night'];  //Lunch if it is there
                        foreach ($mealOrder as $mealType) {
                            if (isset($meals[$mealType])):
                                $menuItem = $meals[$mealType];
                                ?>
                                <div class="bg-white rounded-lg shadow-md p-4">
                                    <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($mealType) ?></h5>
                                    <p class="text-gray-700"><?= htmlspecialchars($menuItem['menu']) ?></p>
                                    <?php if ($menuItem['imgurl']): ?>
                                        <img src="<?= htmlspecialchars($menuItem['imgurl']) ?>" alt="<?= htmlspecialchars($menuItem['category']) ?>" class="w-full h-32 object-cover rounded-md mt-2">
                                    <?php else: ?>
                                        <p class="text-gray-500 italic mt-2">No Image</p>
                                    <?php endif; ?>
                                </div>
                            <?php
                            endif;
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Message for Non-Subscribers -->
            <div class="text-center">
                <p class="text-gray-700 mb-8">To view the weekly menu, please <a href="login.php" class="text-green-600 hover:underline">login</a> and subscribe.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Subscription Form Section -->
<?php if (isLoggedIn() && getUserRole() === 'Customer' && !$subscription): ?>
    <section id="subscribe" class="bg-white py-16">
        <div class="container mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-8">Subscribe to a Meal Plan</h2>
            <form action="subscribe.php" method="POST" class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Meal Plan Category -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-medium mb-2">Select Your Health Goals</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="High BP" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Control High BP</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="Low BP" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Control Low BP</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="Diabetes" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Manage Diabetes</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="Thyroid" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Manage Thyroid</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="Weight Gain" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Weight Gain</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="health_goals[]" value="Weight Loss" class="form-checkbox h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Weight Loss</span>
                            </label>
                        </div>
                    </div>

                    <!-- Height -->
                    <div>
                        <label for="height" class="block text-gray-700 font-medium mb-2">Height (in cm)</label>
                        <input type="number" name="height" id="height" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200" required>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-gray-700 font-medium mb-2">Weight (in kg)</label>
                        <input type="number" name="weight" id="weight" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200" required>
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="age" class="block text-gray-700 font-medium mb-2">Age</label>
                        <input type="number" name="age" id="age" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200" required>
                    </div>

                    <!-- Subscription Plan -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-medium mb-2">Select Subscription Plan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="plan" value="Weekly" class="form-radio h-5 w-5 text-green-600 rounded" required>
                                <span class="text-gray-700">Weekly Plan</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="plan" value="Monthly" class="form-radio h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Monthly Plan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="col-span-2">
                        <label for="notes" class="block text-gray-700 font-medium mb-2">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Any specific dietary requirements or allergies?"></textarea>
                    </div>
                </div>
                <div class="text-center mt-8">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200">
                        Subscribe Now
                    </button>
                </div>
            </form>
        </div>
    </section>
<?php elseif (isLoggedIn() && getUserRole() === 'Customer' && $subscription): ?>
    <!-- Active Subscription: Show Renew Options -->
    <section id="renew" class="bg-white py-16">
        <div class="container mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-8">Renew Your Subscription</h2>
            <p class="text-gray-700 text-center mb-8">Your current subscription is active. Choose a plan to renew:</p>
            <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-8">
                <form action="renew_subscription.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Subscription Plan -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-medium mb-2">Select Subscription Plan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="plan" value="Weekly" class="form-radio h-5 w-5 text-green-600 rounded" required>
                                <span class="text-gray-700">Weekly Plan</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="plan" value="Monthly" class="form-radio h-5 w-5 text-green-600 rounded">
                                <span class="text-gray-700">Monthly Plan</span>
                            </label>
                        </div>
                    </div>
                    <div class="text-center col-span-2">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200">
                            Renew Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>