</main>
    <footer class="bg-gray-100 py-12">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About GreenHive -->
                <div class="mb-6 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">About GreenHive</h3>
                    <p class="text-gray-600">
                        Connecting farmers and consumers for a sustainable food ecosystem. We deliver fresh,
                        organic produce and healthy meal plans right to your doorstep.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="mb-6 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h3>
                    <ul class="list-none pl-0">
                        <li class="mb-2">
                            <a href="<?= SITE_URL ?>/products.php"
                                class="text-green-500 hover:text-green-700 transition duration-300 ease-in-out">Products</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= SITE_URL ?>/meals.php"
                                class="text-green-500 hover:text-green-700 transition duration-300 ease-in-out">Meal
                                Plans</a>
                        </li>
                        <li class="mb-2">
                            <a href="#"
                                class="text-green-500 hover:text-green-700 transition duration-300 ease-in-out">About
                                Us</a>
                        </li>
                        <li>
                            <a href="sreevastha07@gmail.com"
                                class="text-green-500 hover:text-green-700 transition duration-300 ease-in-out">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Us -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Us</h3>
                    <ul class="list-none pl-0">
                        <li class="mb-2">
                            <a href="mailto:info@greenhive.com"
                                class="text-gray-600 hover:text-green-500 transition duration-300 ease-in-out flex items-center">
                                <i class="fas fa-envelope mr-2"></i> mailus
                            </a>
                        </li>
                        <li>
                            <a href="tel:9160128648"
                                class="text-gray-600 hover:text-green-500 transition duration-300 ease-in-out flex items-center">
                                <i class="fas fa-phone mr-2"></i> callus
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-300 pt-8 mt-8 text-center text-gray-600">
                <p>&copy; <?= date('Y') ?> GreenHive. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>

</html>