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
< lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/sty.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../layout/script.js"></script>
    <title>Form Register</title>
</head>
<body>
    <?php include "../layout/header.php" ?>
    <section class="relative h-screen flex items-center justify-center">
        <img src="../assets/bg.png" class="absolute w-full h-full object-cover">
        <div class="relative bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="flex items-center justify-center mb-6">
                <img class="w-12 h-12 mr-2" src="../assets/Logo Mentaly.png" alt="Icon">
                <span class="text-2xl font-bold">MieMe</span>
            </div>
            <h1 class="text-2xl font-bold mb-4">Register</h1>
            
            <form method="post" action="register.php" class="space-y-4" onsubmit="return validatePassword()">
                <div>
                    <label class="block font-semibold">Username:</label>
                    <input type="text" placeholder="Username" name="username" class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                </div>
                <div>
                    <label class="block font-semibold">Fullname:</label>
                    <input type="text" placeholder="Fullname" name="fullname" class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                </div>
                <div>
                    <label class="block font-semibold">Password:</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                        <span id="toggleIcon" onclick="togglePassword('password')" class="absolute right-3 top-2 cursor-pointer">
                            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold">Confirm Password:</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                        <span id="toggleConfirmIcon" onclick="togglePassword('confirm_password')" class="absolute right-3 top-2 cursor-pointer">
                            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
                        </span>
                    </div>
                </div>
                
                <p class="text-sm">
                    Sudah memiliki akun? <a href="login.php" class="text-blue-600 hover:underline">Klik disini</a>
                </p>
                <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Submit</button>
            </form>
        </div>
    </section>


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
</script>
<?php include "../layout/footer.php" ?>
</body>

</html>