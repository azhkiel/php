<?php
$hostname = "localhost";
$username = "root";  // Sebaiknya ganti dengan user khusus aplikasi
$password = "";      // Harap gunakan password yang kuat
$database_name = "mieme";
$port = 3307;        // Pastikan port sesuai dengan server MySQL

// Membuat koneksi
$db = mysqli_connect($hostname, $username, $password, $database_name, $port);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($db, "utf8mb4");
?>