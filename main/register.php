<?php
include "../service/database.php";
session_start();

$register_message = "";

if (isset($_SESSION["is_login"])){
    header("Location: dashboard.php");
}

try {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];

        $hash_password = hash("sha256", $password);
        $sql = "INSERT INTO `users`(`username`,`fullname`, `password`,`role`) VALUES ('$username','$fullname','$hash_password','customer')";
        
        if ($db->query($sql)) {
            $register_message = "🎉 Registration successful! Please login.";
        } else {
            echo "Register failed";
        }
    }
} catch (mysqli_sql_exception) {
    $register_message = "⚠️ Username already exists!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Register | MieMe</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50">
    <?php include "../layout/header.php" ?>
    
    <section class="relative min-h-screen flex items-center justify-center p-4 pt-[90px]">
        <img src="../assets/img/mieme/bg.png" class="absolute w-full h-full object-cover ">
        <!-- Animated floating noodles -->
        <?php include '../layout/decoration.php';?>
        
        <!-- Main Card -->
        <div class="relative bg-white p-8 rounded-2xl shadow-xl max-w-md w-full animate__animated animate__fadeInUp animate__faster backdrop-blur-sm bg-white/90">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8 transform transition-all hover:scale-105">
                <div class="flex items-center justify-center mb-4">
                    <img class="w-16 h-16 mr-3 animate-spin-slow" src="../assets/img/mieme/Logo Mentaly.png" alt="Icon">
                    <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MieMe</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Create Your Account</h1>
                <p class="text-gray-500">Join our easy order Mieme</p>
            </div>
            
            <!-- Register Form -->
            <form method="post" action="register.php" class="space-y-5">
                <!-- Username Field -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="relative">
                        <input type="text" placeholder="Username" name="username" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Fullname Field -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" placeholder="Fullname" name="fullname" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>
                
                <!-- Password Field -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <span id="toggleIcon" onclick="togglePassword('password')" class="absolute right-3 top-3.5 cursor-pointer text-gray-400 hover:text-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>
                
                <!-- Confirm Password Field -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <span id="toggleConfirmIcon" onclick="togglePassword('confirm_password')" class="absolute right-3 top-3.5 cursor-pointer text-gray-400 hover:text-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>
                
                <!-- Login Link -->
                <p class="text-sm text-center text-gray-500">
                    Already have an account? 
                    <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500 hover:underline transition-colors">
                        Login here
                    </a>
                </p>
                
                <!-- Submit Button -->
                <button type="submit" name="register" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    Register Now
                </button>
            </form>
        </div>
    </section>

    <!-- Popup Notification -->
    <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 max-w-sm w-full rounded-xl shadow-2xl transform transition-all duration-300 animate__animated animate__zoomIn">
            <div class="text-center">
                <div id="popup-icon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="popup-message"></h3>
                <button id="close-popup" 
                    class="mt-4 w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Got it!
                </button>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId === 'password' ? 'toggleIcon' : 'toggleConfirmIcon');
            
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>`;
            } else {
                input.type = "password";
                icon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>`;
            }
        }

        // Show popup with animation
        function showPopup(message) {
            const popup = document.getElementById('popup');
            const popupMessage = document.getElementById('popup-message');
            const popupIcon = document.getElementById('popup-icon');
            
            popupMessage.textContent = message;
            
            // Change icon based on message
            if (message.includes("success")) {
                popupIcon.innerHTML = `
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>`;
                popupIcon.classList.remove('bg-blue-100');
                popupIcon.classList.add('bg-green-100');
            } else {
                popupIcon.innerHTML = `
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>`;
                popupIcon.classList.remove('bg-blue-100');
                popupIcon.classList.add('bg-red-100');
            }
            
            popup.classList.remove('hidden');
        }

        // Hide popup
        function hidePopup() {
            document.getElementById('popup').classList.add('hidden');
        }

        // Close popup when clicking the button
        document.getElementById('close-popup').addEventListener('click', hidePopup);

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.querySelector('[name="username"]').value.trim();
            const fullname = document.querySelector('[name="fullname"]').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            
            const errors = [];
            
            if (!username) errors.push("Username is required");
            if (!fullname) errors.push("Full name is required");
            if (!password) errors.push("Password is required");
            if (!confirmPassword) errors.push("Please confirm your password");
            
            if (password !== confirmPassword) {
                errors.push("Passwords don't match");
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                showPopup(errors.join("\n"));
                return false;
            }
            
            return true;
        });

        // Show popup if there's a message from PHP
        <?php if (!empty($register_message)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showPopup(<?= json_encode($register_message) ?>);
        });
        <?php endif; ?>
    </script>
    <?php include "../layout/footer.php" ?>
</body>
</html>