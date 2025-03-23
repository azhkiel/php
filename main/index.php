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
    <title>Landing</title>
</head>
<body>
    <?php include "../layout/header.html" ?>
    <?php include "../layout/section.html" ?>
    <?php include "../layout/menu.html" ?>
    <?php include "../layout/footer.html" ?>
</body>
</html>