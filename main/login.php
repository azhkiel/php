<?php 
include "../service/database.php";
session_start();

if (isset($_SESSION["is_login"])) {
    // Redirect berdasarkan role jika sudah login
    switch ($_SESSION["role"]) {
        case "owner":
            header("Location: ../main/owner/dashOwner.php");
            break;
        case "admin":
            header("Location: ../main/admin/dashAdmin.php");
            break;
        case "customer":
            header("Location: ../main/customer/dashCustomer.php");
            break;
        case "staff":
            header("Location: ../main/staff/dashStaff.php");
            break;
    }
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash_password = hash("sha256", $password);

    $sql = "SELECT id, username, fullname, role FROM users WHERE username = '$username' AND password = '$hash_password'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION["user_id"] = $data["id"];
        $_SESSION["username"] = $data["username"];
        $_SESSION["fullname"] = $data["fullname"];
        $_SESSION["role"] = $data["role"];
        $_SESSION["is_login"] = true;

        // Redirect ke dashboard sesuai role
        switch ($data["role"]) {
            case "owner":
                header("Location: ../main/owner/dashOwner.php");
                break;
            case "admin":
                header("Location: ../main/admin/dashAdmin.php");
                break;
            case "customer":
                header("Location: ../main/customer/dashCustomer.php");
                break;
            case "staff":
                header("Location: ../main/staff/dashStaff.php");
                break;
        }
        exit();
    } else {
        $login_message = "Nama atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/sty.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Form Login</title>
</head>
<body>
    <?php include "../layout/header.php" ?>
    
    <section class="relative h-screen w-full flex items-center justify-center">
        <!-- Background Image -->
        <img src="../assets/bg.png" class="w-full h-full object-cover absolute top-0 left-0 z-0">
    
        <!-- Form Container -->
        <div class="relative z-50 bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="flex items-center justify-center mb-6">
                <img class="w-12 h-12 mr-2" src="../assets/Logo Mentaly.png" alt="Icon">
                <span class="text-2xl font-bold">MieMe</span>
            </div>
            <h1 class="text-2xl font-bold mb-4 text-left">Login</h1>
            
            <form method="post" action="login.php" class="space-y-4">
                <div>
                    <label for="username" class="block font-semibold mb-1">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" 
                    class="w-full px-4 py-2 border border-blue-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
                <div>
                    <label for="password" class="block font-semibold mb-1">Password:</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Password" 
                        class="w-full px-4 py-2 border border-blue-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none">
                        <span id="toggleIcon" onclick="togglePassword()" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
                        </span>
                    </div>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:underline block text-right">Lupa Kata Sandi?</a>
                <button type="submit" name="login" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                    Submit
                </button>
                <p class="text-gray-600 text-sm text-center mt-2">
                    Belum punya akun? 
                    <a href="register.php" class="text-blue-600 hover:underline">Daftar di sini</a>
                </p>
            </form>
        </div>
    </section>

    <!-- Pop-up -->
    <div id="popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-5 w-96 rounded-md shadow-lg text-center">
            <h3 class="text-lg font-medium text-gray-900" id="popup-message"></h3>
            <button id="close-popup" 
                class="mt-4 w-full px-4 py-2 bg-blue-500 text-white font-medium rounded-md shadow-sm hover:bg-blue-700">
                Close
            </button>
        </div>
    </div>

<script>
// Function to show the pop-up
function showPopup(message) {
    document.getElementById('popup-message').textContent = message;
    document.getElementById('popup').classList.remove('hidden');
}
// Function to hide the pop-up
function hidePopup() {
    document.getElementById('popup').classList.add('hidden');
}

// Event listener for the close button
document.getElementById('close-popup').addEventListener('click', hidePopup);
function togglePassword() {
    var passwordField = document.getElementById("password");
    var toggleIcon = document.getElementById("toggleIcon");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.innerHTML = `
            <img src="../assets/eye-open.svg" class="w-6 h-6" alt="eye-open">
        `;
    } else {
        passwordField.type = "password";
        toggleIcon.innerHTML = `
            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
        `;
    }
}

// Check if there's a message to display
<?php if (!empty($login_message)): ?>
document.addEventListener('DOMContentLoaded', function() {
    showPopup(<?= json_encode($login_message) ?>);
});
<?php endif; ?>
</script>

<?php include "../layout/footer.php" ?>
</body>
</html>