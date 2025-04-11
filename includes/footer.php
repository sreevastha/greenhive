<footer class="bg-gray-900 text-white py-8">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <!-- Footer Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- About Us -->
            <div>
                <h3 class="text-lg font-bold mb-4">About Us</h3>
                <p class="text-gray-400 text-sm">
                    GreenHive connects urban consumers with fresh, organic produce and health-focused meals. We're committed to sustainability and empowering local communities.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><a href="about.php" class="hover:text-green-500 transition duration-300">About Us</a></li>
                    <li><a href="products.php" class="hover:text-green-500 transition duration-300">Products</a></li>
                    <li><a href="meals.php" class="hover:text-green-500 transition duration-300">Meal Plans</a></li>
                    <li><a href="contact.php" class="hover:text-green-500 transition duration-300">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                <p class="text-gray-400 text-sm">
                    Email: support@greenhive.com<br>
                    Phone: +1 (123) 456-7890<br>
                    Address: 123 Green Street, Eco City, Earth
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center text-gray-500 text-sm">
            &copy; <?php echo date('Y'); ?> GreenHive. All rights reserved.
        </div>
    </div>
</footer>

<!-- Chatbot -->
<div id="chatbot-container" class="fixed bottom-4 right-4 z-50">
    <!-- Chatbot Icon -->
    <button id="chatbot-icon" class="bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition duration-300 ease-in-out transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12c0 1.84.64 3.53 1.7 4.88l-1.42 4.29c-.25.75.5 1.5 1.25 1.25l4.29-1.42C8.47 19.36 10.66 20 12 20c5.52 0 10-4.48 10-10S17.52 2 12 2z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden fixed bottom-20 right-4 w-80 bg-white rounded-lg shadow-xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 hover:scale-100">
        <div class="bg-green-500 text-white px-4 py-3 font-semibold">Chat with us!</div>
        <div id="chatBox" class="p-4 h-64 overflow-y-auto space-y-4">
            <p class="text-gray-700">Hello! How can we assist you today?</p>
        </div>
        <div class="border-t border-gray-200 p-4 flex">
            <input id="userInput" type="text" placeholder="Type a message..." class="w-full px-3 py-2 border rounded-l-lg bg-gray-100 focus:outline-none focus:border-green-500 placeholder-gray-500 text-sm">
            <button onclick="sendMessage()" class="bg-green-500 text-white px-4 py-2 rounded-r-lg hover:bg-green-600 transition duration-300">Send</button>
        </div>
    </div>
</div>

<!-- JavaScript for Chatbot -->
<script>
    // Toggle Chat Window
    const chatbotIcon = document.getElementById('chatbot-icon');
    const chatWindow = document.getElementById('chat-window');

    chatbotIcon.addEventListener('click', () => {
        chatWindow.classList.toggle('hidden');
        loadChatHistory(); // Load chat history when chat window is opened
    });

    // Save Chat History to localStorage
    function saveChatHistory(chatBox) {
        localStorage.setItem('chatHistory', chatBox.innerHTML);
    }

    // Load Chat History from localStorage
    function loadChatHistory() {
        const chatBox = document.getElementById('chatBox');
        const savedHistory = localStorage.getItem('chatHistory');
        if (savedHistory) {
            chatBox.innerHTML = savedHistory;
        }
    }

    // Send Message Functionality
    async function sendMessage() {
        const userInput = document.getElementById('userInput').value;
        const chatBox = document.getElementById('chatBox');

        if (!userInput.trim()) return; // Ignore empty messages

        // Display User Message
        const userMessage = document.createElement('div');
        userMessage.className = 'bg-blue-500 text-white p-2 rounded-xl mb-2 self-end max-w-xs';
        userMessage.textContent = userInput;
        chatBox.appendChild(userMessage);

        // Display Bot Loading Message
        const botMessage = document.createElement('div');
        botMessage.className = 'bg-gray-300 text-black p-2 rounded-xl mb-2 self-start max-w-xs';
        botMessage.textContent = "Thinking...";
        chatBox.appendChild(botMessage);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            // Fetch response from PHP backend
            const response = await fetch('api_handler.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: userInput }),
            });

            const data = await response.json();

            // Update Bot Response
            botMessage.textContent = data.response || "Sorry, I couldn't understand. Please ask again!";
        } catch (error) {
            botMessage.textContent = "Error connecting to the AI service.";
            console.error("API Error:", error);
        }

        // Clear input field and save chat history
        document.getElementById('userInput').value = '';
        saveChatHistory(chatBox);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Load chat history when the page loads
    window.onload = () => {
        loadChatHistory();
    };
</script>

</body>
</html>