<?php 
    // Menghubungkan ke file database.php untuk menyambungkan ke basis data
include "../service/database.php";

// Memulai sesi PHP
session_start();

// Inisialisasi variabel untuk menyimpan pesan login
$login_mesegge = "";

// Memeriksa apakah pengguna sudah login. Jika iya, langsung dialihkan ke dashboard.php
if (isset($_SESSION["is_login"])){
    header("Location: dashboard.php");
}

// Memeriksa apakah form login telah disubmit
if (isset($_POST['login'])){
    // Mengambil data username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengenkripsi password menggunakan algoritma hash SHA-256
    $hash_password = hash("sha256", $password);

    // Query untuk mencari user dengan username dan password yang cocok di tabel 'akunphp'
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hash_password'";
    $result = $db->query($sql); // Menjalankan query

    // Memeriksa apakah ada hasil yang ditemukan
    if ($result->num_rows > 0) {
        // Mengambil data dari hasil query
        $data = $result->fetch_assoc();

        // Menyimpan username dan status login dalam sesi
        $_SESSION["username"] = $data["username"];
        $_SESSION["is_login"] = true;

        // Menampilkan username (debugging purposes, lebih baik dihapus di produksi)
        echo $data['username'];

        // Mengarahkan pengguna ke dashboard.php
        header("Location: dashboard.php");
    } else {
        // Menyimpan pesan error jika login gagal
        $login_mesegge = "nama atau password salah!";
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
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.innerHTML = `
                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5c5 0 9 5 9 6s-4 6-9 6-9-5-9-6 4-6 9-6Zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z"/>
                    </svg>
                `;
            } else {
                passwordField.type = "password";
                toggleIcon.innerHTML = `
                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                `;
            }
        }
    </script>
    <title>Form Login</title>
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
        <h1 class="text-2xl font-bold mb-4">Login Form</h1>

        
        <form method="post" action="login.php" class="space-y-4">
            <div class="text-left">
                <label for="username" class="block font-semibold mb-1">Username:</label>
                <input type="text" id="username" name="username" placeholder="Username" 
                class="w-full px-4 py-2 border border-blue-300 rounded-md focus:border-blue-600 hover:border-blue-700 outline-none">
            </div>
            <div>
                <label for="password" class="block text-left font-semibold">Password:</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-md focus:border-blue-600 hover:border-blue-600 outline-none">
                    <span id="toggleIcon" onclick="togglePassword()" style="cursor: pointer; position: absolute; right: 10px;">
                        <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </span>
                </div>
            </div>


            <a href="#" class="text-sm text-blue-600 hover:underline mb-4 block text-right">Lupa Kata Sandi?</a>
            <button type="submit" name="login" 
                class="w-full bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-md">
                Submit
            </button>

            <!-- Tambahan teks "Belum punya akun?" -->
            <p class="text-gray-700 text-sm text-center mt-2">
                Belum punya akun? 
                <a href="register.php" class="text-blue-600 hover:underline">Daftar di sini</a>
            </p>
        </form>
    </div>
</section>

<!-- Add this container for the pop-up at the end of the body -->
<div id="popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
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
//hai
// Function to hide the pop-up
function hidePopup() {
    document.getElementById('popup').classList.add('hidden');
}

// Event listener for the close button
document.getElementById('close-popup').addEventListener('click', hidePopup);

// Check if there's a message to display
<?php if (!empty($login_mesegge)): ?>
document.addEventListener('DOMContentLoaded', function() {
    showPopup(<?= json_encode($login_mesegge) ?>);
});
<?php endif; ?>
</script>

<?php include "../layout/footer.html" ?>
</body>

</html>