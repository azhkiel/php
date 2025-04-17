<?php
include "../service/database.php";

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$register_message = "";

if (isset($_SESSION["is_login"])) {
    header("Location: dashboard.php");
    exit();
}

try {
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $fullname = trim($_POST['fullname']);
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate inputs
        $errors = [];
        
        if (empty($username)) {
            $errors[] = "Username is required";
        }
        
        if (empty($fullname)) {
            $errors[] = "Full name is required";
        }
        
        if (empty($phone)) {
            $errors[] = "Phone number is required";
        }
        
        // Format phone number consistently
        if (substr($phone, 0, 2) === "62") {
            // Keep as is
        } elseif (substr($phone, 0, 1) === "0") {
            // Convert 08xxx to 628xxx
            $phone = "62" . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === "8") {
            // Convert 8xxx to 628xxx
            $phone = "62" . $phone;
        } else {
            $errors[] = "Invalid phone number format. Use format: 08xxxxxxxxxx or 628xxxxxxxxxx";
        }
        
        // Phone number validation
        if (!preg_match('/^62\d{9,13}$/', $phone)) {
            $errors[] = "Phone number must be in format 628xxxxxxxxxx with 10-14 digits total";
        }
        
        // Password validation
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }
        
        if ($password !== $confirm_password) {
            $errors[] = "Passwords don't match";
        }
        
        // If there are validation errors
        if (!empty($errors)) {
            $register_message = "⚠️ " . implode("<br>⚠️ ", $errors);
        } else {
            // Hash password
            $hash_password = hash("sha256", $password);

            // First, check if username already exists
            $check_stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $check_stmt->bind_param('s', $username);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $register_message = "⚠️ Username already exists!";
            } else {
                // Insert the new user
                $stmt = $db->prepare("INSERT INTO `users`(`username`, `fullname`, `phone`, `password`, `role`) 
                    VALUES (?, ?, ?, ?, 'customer')");
                $stmt->bind_param('ssss', $username, $fullname, $phone, $hash_password);
                
                if ($stmt->execute()) {
                    // Get the user ID of the newly created user
                    $user_id = $db->insert_id;

                    // Store user_id in session for OTP verification
                    $_SESSION['temp_user_id'] = $user_id;
                    $_SESSION['temp_phone'] = $phone;
                    unset($_SESSION['otp_sent']); // Make sure we generate a new OTP

                    // Redirect to OTP verification page
                    header("Location: verify_otp.php");
                    exit();
                } else {
                    $register_message = "⚠️ Registration failed: " . $db->error;
                }
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    // Check if error is duplicate entry for username
    if ($db->errno == 1062 && strpos($e->getMessage(), 'username')) {
        $register_message = "⚠️ Username already exists!";
    } else {
        $register_message = "⚠️ Registration failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
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
        <?php include '../layout/decoration.php'; ?>

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

            <!-- Registration Messages -->
            <?php if (!empty($register_message)): ?>
            <div class="mb-4 p-3 rounded <?= strpos($register_message, '⚠️') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                <?= $register_message ?>
            </div>
            <?php endif; ?>

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
                    <input type="text" placeholder="Full Name" name="fullname"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>

                <!-- Phone Field -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="relative">
                        <input type="text" placeholder="628xxxxxxxxxx" name="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Format: 628xxxxxxxxxx or 08xxxxxxxxxx (no spaces or special characters)</p>
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
                    <p class="text-xs text-gray-500">Minimum 6 characters</p>
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

    <?php include "../layout/footer.php" ?>

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
    </script>
</body>
</html>