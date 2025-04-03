<?php
    // Menghubungkan ke file database.php untuk menyambungkan ke database
include "../service/database.php";

// Memulai sesi PHP
session_start();

// Inisialisasi variabel untuk pesan registrasi
$register_message = "";

// Memeriksa apakah pengguna sudah login. Jika iya, langsung dialihkan ke dashboard.php
if (isset($_SESSION["is_login"])){
    header("Location: dashboard.php");
}

// Blok try-catch untuk menangani kemungkinan kesalahan SQL
try {
    // Memeriksa apakah form register telah disubmit
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];

        $hash_password = hash("sha256", $password);
        $sql = "INSERT INTO `users`(`username`,`fullname`, `password`,`role`) VALUES ('$username','$fullname','$hash_password','customer')";
        
        // Menjalankan query dan memeriksa keberhasilannya
        if ($db->query($sql)) {
            $register_message = "Register success";
        } else {
            echo "Register failed";
        }
    }
} catch (mysqli_sql_exception) {
    // Menangani kesalahan, seperti ketika username sudah terdaftar
    $register_message = "username already registered!";
}
?>
<!DOCTYPE html>
< lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
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
            
            <form method="post" action="register.php" class="space-y-4">
                <div>
                    <label class="block font-semibold">Username:</label>
                    <input type="text" placeholder="Username" name="username" 
                        class="w-full px-4 py-2 border rounded-md focus:border-blue-600"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                
                <div>
                    <label class="block font-semibold">Fullname:</label>
                    <input type="text" placeholder="Fullname" name="fullname" 
                        class="w-full px-4 py-2 border rounded-md focus:border-blue-600"
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>
                
                <div>
                    <label class="block font-semibold">Password:</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Password" 
                            class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                        <span id="toggleIcon" onclick="togglePassword('password')" class="absolute right-3 top-2 cursor-pointer">
                            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
                        </span>
                    </div>
                </div>
                
                <div>
                    <label class="block font-semibold">Confirm Password:</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" 
                            class="w-full px-4 py-2 border rounded-md focus:border-blue-600">
                        <span id="toggleConfirmIcon" onclick="togglePassword('confirm_password')" class="absolute right-3 top-2 cursor-pointer">
                            <img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">
                        </span>
                    </div>
                </div>
                <p class="text-sm">
                    Sudah memiliki akun? <a href="login.php" class="text-blue-600 hover:underline">Klik disini</a>
                </p>
                <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                    Register
                </button>
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
    // Fungsi toggle yang konsisten
    function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(inputId === 'password' ? 'toggleIcon' : 'toggleConfirmIcon');
        
        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = `<img src="../assets/eye-open.svg" class="w-6 h-6" alt="eye-open">`;
        } else {
            input.type = "password";
            icon.innerHTML = `<img src="../assets/eye-close.svg" class="w-6 h-6" alt="eye-close">`;
        }
    }
    // Validasi password
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        
        if (password !== confirmPassword) {
            showPopup("Password dan Confirm Password tidak sesuai!");
            return false;
        }
        return true;
    }

    // Fungsi popup (pastikan ini ada)
    function showPopup(message) {
        document.getElementById('popup-message').textContent = message;
        document.getElementById('popup').classList.remove('hidden');
    }

    function hidePopup() {
        document.getElementById('popup').classList.add('hidden');
    }

    // Event listener
    document.getElementById('close-popup').addEventListener('click', hidePopup);

    // Tampilkan popup jika ada pesan dari PHP
    <?php if (!empty($register_message)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        showPopup(<?= json_encode($register_message) ?>);
    });
    <?php endif; ?>
</script>
<script>
// Validasi sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const username = document.querySelector('[name="username"]').value.trim();
    const fullname = document.querySelector('[name="fullname"]').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    
    const errors = [];
    
    if (!username) errors.push("Username harus diisi ");
    if (!fullname) errors.push("Nama lengkap harus diisi");
    if (!password) errors.push("Password harus diisi");
    if (!confirmPassword) errors.push("Konfirmasi password harus diisi");
    
    if (errors.length > 0) {
        e.preventDefault(); // Menghentikan form submit
        showPopup(errors.join("<br>"));
        return false;
    }
    
    // Validasi password match
    if (password !== confirmPassword) {
        e.preventDefault();
        showPopup("Password dan konfirmasi password tidak sama");
        return false;
    }
    
    return true;
});

// Fungsi untuk menandai field yang error
function markFieldErrors() {
    const fields = [
        { name: 'username', label: 'Username' },
        { name: 'fullname', label: 'Nama lengkap' },
        { name: 'password', label: 'Password' },
        { name: 'confirm_password', label: 'Konfirmasi password' }
    ];
    
    fields.forEach(field => {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (input && input.value.trim() === '') {
            input.classList.add('border-red-500');
            input.insertAdjacentHTML('afterend', 
                `<p class="text-red-500 text-sm mt-1">${field.label} harus diisi</p>`);
        }
    });
}
</script>
<?php include "../layout/footer.php" ?>
</body>

</html>