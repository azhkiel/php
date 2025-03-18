<?php
// Menghubungkan ke database
include_once("koneksi.php");

// Query untuk mengambil semua data dari tabel tb_buku
$query = "SELECT * FROM tb_buku";
$hasil = mysqli_query($conn, $query);

// Loop untuk menampilkan judul buku dari hasil query
while ($data = mysqli_fetch_array($hasil)) { 
    echo $data['judul_buku'] . "<br/>"; // Menampilkan judul buku dengan pemisah baris baru
}
?>
