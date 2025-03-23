<?php
    // Menghubungkan ke file database.php untuk menyambungkan ke database
include "../service/database.php";

// Memulai sesi PHP
session_start();

// Inisialisasi variabel untuk pesan registrasi
$register_messege = "";

// Memeriksa apakah pengguna sudah login. Jika iya, langsung dialihkan ke dashboard.php
if (isset($_SESSION["is_login"])){
    header("Location: dashboard.php");
}

// Blok try-catch untuk menangani kemungkinan kesalahan SQL
try {
    // Memeriksa apakah form register telah disubmit
    if (isset($_POST['register'])) {
        // Mengambil data username dan password dari form
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];

        // Mengenkripsi password menggunakan algoritma hash SHA-256
        $hash_password = hash("sha256", $password);

        // Query untuk menyisipkan data user baru ke tabel 'akunphp'
        $sql = "INSERT INTO `users`(`username`,`fullname`, `password`) VALUES ('$username','$fullname','$hash_password')";
        
        // Menjalankan query dan memeriksa keberhasilannya
        if ($db->query($sql)) {
            $register_messege = "Register success";
        } else {
            echo "Register failed";
        }
    }
} catch (mysqli_sql_exception) {
    // Menangani kesalahan, seperti ketika username sudah terdaftar
    $register_messege = "username already registered!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/sty.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../layout/script.js"></script>
    <title>Form Register</title>
</head>
<body>
    <?php include "../layout/header.html" ?>
    <section class="relative h-screen w-full flex items-center justify-center">
    <!-- Background Image -->
    <img src="../assets/background.jpg" class="w-full h-full object-cover absolute top-0 left-0 z-0">

    <!-- Form Container -->
    <div class="relative z-100 bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="flex items-center justify-center mb-6">
            <img class="w-12 h-12 mr-2" src="../assets/Logo Mentaly.png" alt="Icon">
            <a class="text-2xl font-bold">MieMe</a>
        </div>
        <h1 class="text-2xl font-bold mb-4">Register</h1>
        
        <form method="post" action="register.php" class="space-y-4" onsubmit="return validatePassword()">
            <div>
                <label for="username" class="block text-left font-semibold">Username:</label>
                <input type="text" placeholder="Username" name="username" class="w-full px-4 py-2 border rounded-md focus:border-blue-600 hover:border-blue-600 outline-none ">
            </div>
            <div>
                <label for="fullname" class="block text-left font-semibold">Fullname:</label>
                <input type="text" placeholder="fullname" name="fullname" class="w-full px-4 py-2 border rounded-md focus:border-blue-600 hover:border-blue-600 outline-none ">
            </div>
            <div>
                <label for="password" class="block text-left font-semibold">Password:</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-md focus:border-blue-600 hover:border-blue-600 outline-none">
                    <span id="toggleIcon" onclick="togglePassword('password')" style="cursor: pointer; position: absolute; right: 10px;">
                        <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div>
                <label for="confirm_password" class="block text-left font-semibold">Confirm Password:</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded-md focus:border-blue-600 hover:border-blue-600 outline-none">
                    <span id="toggleConfirmIcon" onclick="togglePassword('confirm_password')" style="cursor: pointer; position: absolute; right: 10px;">
                        <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </span>
                </div>
            </div>
            
            <p class="text-gray-700 text-sm">
                Sudah memiliki akun? 
                <a href="login.php" class="text-blue-600 hover:underline">Klik disini</a>
            </p>
            <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Submit</button>
        </form>
    </div>
</section>

<!-- Add this container for the pop-up at the end of the body -->
<div id="popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="popup-message"></h3>
            <div class="mt-2 px-7 py-3">
                <button id="close-popup" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Close
                </button>
            </div>
        </div>
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

    // Check if there's a message to display
    <?php if (!empty($register_messege)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        showPopup(<?= json_encode($register_messege) ?>);
    });
    <?php endif; ?>
</script>
<script>
        function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            input.type = input.type === "password" ? "text" : "password";
        }

        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            
            if (password !== confirmPassword) {
                showPopup("password dan confirm password tidak sesuai!")
                return false;
            }
            return true;
        }
    </script>


<?php include "../layout/footer.html" ?>
</body>

</html>