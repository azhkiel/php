<?php
include "../service/database.php";
date_default_timezone_set('Asia/Jakarta'); // Ganti dengan timezone yang sesuai

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is coming from registration
if (!isset($_SESSION['temp_user_id']) || !isset($_SESSION['temp_phone'])) {
    header("Location: register.php");
    exit();
}

$user_id = $_SESSION['temp_user_id'];
$phone = $_SESSION['temp_phone'];
$message = "";
$resend_allowed = true;
$remaining_time = 0;
$debug_mode = true; // Set to false in production

// Function to generate and save OTP
function generateAndSaveOTP($db, $user_id)
{
    // Generate a 6-digit OTP
    $otp = sprintf("%06d", rand(0, 999999)); // Ensure 6 digits with leading zeros

    // Calculate expiry time (5 minutes from now)
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // Mark any existing OTPs as used
    $update_stmt = $db->prepare("UPDATE otp SET used = 1 WHERE user_id = ? AND used = 0");
    $update_stmt->bind_param('i', $user_id);
    $update_stmt->execute();

    // Store OTP in database
    $stmt = $db->prepare("INSERT INTO `otp`(`user_id`, `otp`, `expires_at`) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $user_id, $otp, $expires_at);

    if (!$stmt->execute()) {
        error_log("Error saving OTP: " . $stmt->error);
    }

    return $otp;
}

// Function to send OTP via API
function sendOTP($phone, $otp)
{
    // Format phone number if needed
    if (substr($phone, 0, 2) === "08") {
        $phone = "62" . substr($phone, 1); // Convert 08xxx to 628xxx
    }

    // This is a placeholder for your actual SMS sending code
    $curl = curl_init();
    $data = [
        'target' => $phone,
        'message' => "Your MieMe verification code is: " . $otp
    ];

    curl_setopt(
        $curl,
        CURLOPT_HTTPHEADER,
        array(
            "Authorization: JXKvDkWwH75t73vE7UD7", // Replace with your actual API token
        )
    );
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_URL, "https://api.fonnte.com/send");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($curl);
    curl_close($curl);

    // Log API response for debugging
    if ($result === false) {
        error_log("SMS API call failed: " . curl_error($curl));
        return false;
    } else {
        error_log("SMS API response: " . $result);
        return true;
    }
}

// // Check if there's an existing OTP that is not expired
// $stmt = $db->prepare("SELECT id, otp, expires_at FROM otp WHERE user_id = ? AND used = 0 ORDER BY created_at DESC LIMIT 1");
// $stmt->bind_param('i', $user_id);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     $otp_row = $result->fetch_assoc();
//     $current_time = new DateTime();
//     $expiry_time = new DateTime($otp_row['expires_at']);

//     if ($current_time < $expiry_time) {
//         $remaining_seconds = $expiry_time->getTimestamp() - $current_time->getTimestamp();
//         $resend_allowed = false;
//         $remaining_time = $remaining_seconds;
//     }
// }

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']); // Trim any whitespace

    // For debugging only
    if ($debug_mode) {
        error_log("Entered OTP: " . $entered_otp);
        error_log("User ID: " . $user_id);
    }

    // Check if OTP is valid
    $stmt = $db->prepare("SELECT id, otp FROM otp WHERE user_id = ? AND otp = ? AND used = 0 AND expires_at > NOW()");

    if (!$stmt) {
        error_log("Prepare failed: (" . $db->errno . ") " . $db->error);
        $message = "⚠️ Technical error. Please try again.";
    } else {
        $stmt->bind_param('is', $user_id, $entered_otp);

        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $message = "⚠️ Verification failed. Please try again.";
        } else {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // OTP is valid - mark it as used
                $otp_id = $result->fetch_assoc()['id'];
                $update_stmt = $db->prepare("UPDATE otp SET used = 1 WHERE id = ?");
                $update_stmt->bind_param('i', $otp_id);
                $update_stmt->execute();

                // // Update user status to verified
                // $verify_stmt = $db->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
                // $verify_stmt->bind_param('i', $user_id);
                // $verify_stmt->execute();

                // Set session for login
                $_SESSION["is_login"] = true;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["phone"] = $phone;

                // Get user data from database (role and fullname)
                $user_stmt = $db->prepare("SELECT role, fullname AS fullname FROM users WHERE id = ?");
                $user_stmt->bind_param('i', $user_id);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();

                if ($user_result->num_rows > 0) {
                    $user_data = $user_result->fetch_assoc();
                    $_SESSION["role"] = $user_data['role']; // Set the role in session
                    $_SESSION["fullname"] = $user_data['fullname']; // Set the fullname in session
                } else {
                    // Default values if not found
                    $_SESSION["role"] = "customer";
                    $_SESSION["fullname"] = "Customer";
                }

                // Clean up temporary session variables
                unset($_SESSION['temp_user_id']);
                unset($_SESSION['temp_phone']);
                unset($_SESSION['otp_sent']);

                // Redirect to dashboard
                header("Location: ../main/customer/dashCustomer.php");
                exit();
            } else {
                $message = "⚠️ Invalid or expired OTP. Please try again.";

                // For debugging
                // if ($debug_mode) {
                //     // Get all recent OTPs for this user
                //     $debug_stmt = $db->prepare("SELECT id, otp, expires_at, used, created_at FROM otp WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                //     $debug_stmt->bind_param('i', $user_id);
                //     $debug_stmt->execute();
                //     $debug_result = $debug_stmt->get_result();

                //     echo "<div class='bg-yellow-100 p-4 mb-4 rounded-md'>";
                //     echo "<h3 class='font-bold'>Debug: Recent OTPs for this user</h3>";
                //     echo "<table class='w-full text-sm'><tr><th>ID</th><th>OTP</th><th>Expires At</th><th>Used</th><th>Created At</th></tr>";

                //     while ($debug_row = $debug_result->fetch_assoc()) {
                //         echo "<tr>";
                //         echo "<td>" . $debug_row['id'] . "</td>";
                //         echo "<td>" . $debug_row['otp'] . "</td>";
                //         echo "<td>" . $debug_row['expires_at'] . "</td>";
                //         echo "<td>" . ($debug_row['used'] ? 'Yes' : 'No') . "</td>";
                //         echo "<td>" . $debug_row['created_at'] . "</td>";
                //         echo "</tr>";
                //     }

                //     echo "</table>";
                //     echo "<p class='mt-2'>Current time: " . date('Y-m-d H:i:s') . "</p>";
                //     echo "</div>";
                // }
            }
        }
    }
}

// Handle resend OTP
if (isset($_POST['resend_otp']) && $resend_allowed) {
    // Mark any existing OTPs as used
    $update_stmt = $db->prepare("UPDATE otp SET used = 1 WHERE user_id = ? AND used = 0");
    $update_stmt->bind_param('i', $user_id);
    $update_stmt->execute();

    // Generate and save new OTP
    $new_otp = generateAndSaveOTP($db, $user_id);

    // Send OTP
    $send_result = sendOTP($phone, $new_otp);

    if ($send_result) {
        $message = "🎉 A new OTP has been sent to your phone!";
        $resend_allowed = false;
        $remaining_time = 300; // 5 minutes in seconds

        // Show OTP in debug mode
        if ($debug_mode) {
            $message .= " (Debug: OTP is " . $new_otp . ")";
        }
    } else {
        $message = "⚠️ Failed to send OTP. Please try again.";
    }
}

// Handle initial OTP send if it hasn't been sent yet
if (!isset($_SESSION['otp_sent'])) {
    $initial_otp = generateAndSaveOTP($db, $user_id);
    $send_result = sendOTP($phone, $initial_otp);

    if ($send_result) {
        $_SESSION['otp_sent'] = true;

        // Show OTP in debug mode
        if ($debug_mode) {
            $message = "Debug: Initial OTP is " . $initial_otp;
        }
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
    <title>Verify OTP | MieMe</title>
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
        <img src="../assets/img/mieme/bg.png" class="absolute w-full h-full object-cover">
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
                <h1 class="text-2xl font-bold text-gray-800">Verify Your Account</h1>
                <p class="text-gray-500">Enter the OTP sent to <?= substr($phone, 0, 4) . '***' . substr($phone, -4) ?></p>
                <?php if ($debug_mode): ?>
                    <div class="mt-2 text-xs text-gray-500">
                        User ID: <?= $user_id ?> | Phone: <?= $phone ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- OTP Form -->
            <form method="post" action="verify_otp.php" class="space-y-6">
                <!-- OTP Input -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Enter OTP Code</label>
                    <div class="flex space-x-2 w-full justify-center">
                        <input type="text" name="otp" maxlength="6" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center text-xl tracking-widest" placeholder="123456" required>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="p-3 rounded <?= strpos($message, '⚠️') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <div>
                    <button type="submit" name="verify_otp" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105">
                        Verify Account
                    </button>
                </div>

                <!-- Resend OTP -->
                <div class="text-center">
                    <?php if ($resend_allowed): ?>
                        <button type="submit" name="resend_otp" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Didn't receive the code? Resend OTP
                        </button>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">
                            Resend OTP in <span id="timer">5:00</span>
                        </p>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="login.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Back to Login
                </a>
            </div>
        </div>
    </section>

    <?php include "../layout/footer.php" ?>

    <script>
        // Timer function for OTP resend
        document.addEventListener('DOMContentLoaded', function() {
            let remainingTime = <?= $remaining_time ?>;
            const timerElement = document.getElementById('timer');

            if (remainingTime > 0 && timerElement) {
                const timerInterval = setInterval(function() {
                    remainingTime--;

                    const minutes = Math.floor(remainingTime / 60);
                    const seconds = remainingTime % 60;

                    timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                    if (remainingTime <= 0) {
                        clearInterval(timerInterval);
                        location.reload(); // Reload to enable resend button
                    }
                }, 1000);
            }
        });
    </script>
</body>

</html>