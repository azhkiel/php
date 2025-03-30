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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Landing - Page</title>
</head>
<body>
    <?php include "../layout/header.php" ?>
    <main class="pt-[80px]">
        <div id="content">
        <section class="relative w-full h-screen bg-cover bg-center flex items-center px-12" style="background-image: url('../assets/bg.png');">
            <!-- Overlay Hitam Transparan -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            <div class="relative flex w-full max-w-screen-xl mx-auto">
                <!-- Bagian Kiri: Teks -->
                <div class="w-1/2 text-white">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="../assets/Mieme.png" alt="judul" class="h-20 w-auto">
                    </div>
                    <p class="text-lg mb-4">
                        MieMe, dikelola oleh PT Keluarga Cemara, adalah restoran mie pedas No. 1 di Indonesia yang menghadirkan cita rasa khas dan pengalaman kuliner yang seru. 
                        Sebagai brand lokal, kami bangga menyajikan mie berkualitas dengan berbagai level kepedasan yang bisa disesuaikan selera—nikmat, pedas, dan bikin nagih!
                    </p>
                    <p class="text-lg mb-6">
                        Kami selalu berkomitmen untuk memberikan pengalaman kuliner terbaik bagi pelanggan dengan menggunakan bahan-bahan pilihan dan resep autentik.
                        Dengan suasana yang nyaman dan harga yang terjangkau, MieMe siap menjadi pilihan favorit pecinta mie di seluruh Indonesia!
                    </p>
                    <a href="#" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg inline-block">Lihat Menu</a>
                </div>

                <!-- Bagian Kanan: Gambar Mie -->
                <div class="w-1/2 flex justify-center">
                    <img src="../assets/mieTP.png" alt="Mie Pedas" class="w-[500px] rounded-lg shadow-lg">
                </div>
            </div>
        </section>
        <section id="promo">
            <?php include '../layout/menuData.php'; ?>
        </section>
        <section id="about">
            <?php include '../layout/about.php'; ?>
        </section>
        <?php include "../layout/section.php" ?>
    </div>
</main>
    <?php include "../layout/footer.php" ?>
</body>
<script>
    function loadSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            const yOffset = -80; // Sesuaikan offset (misal: tinggi header)
            const y = section.getBoundingClientRect().top + window.scrollY + yOffset;
            window.scrollTo({ top: y, behavior: "smooth" });
        }
    }
</script>
</html>
