<?php 
// Menghubungkan ke database
include_once("koneksi.php");

// Mengambil data dari form
$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];
$tahun = $_POST['tahun_terbit'];
$kategori = $_POST['kategori'];

// Query untuk menambahkan data ke dalam tabel tb_buku
$query = "INSERT INTO tb_buku (judul_buku, pengarang, tahun_terbit, kategori) 
        VALUES ('$judul', '$pengarang', '$tahun', '$kategori')";

// Menjalankan query
$hasil = mysqli_query($conn, $query);

// Mengecek apakah query berhasil dijalankan
if ($hasil) {
    // Jika berhasil, redirect ke halaman utama
    header('location:indexcreateurut.php');
} else {
    // Jika gagal, tampilkan pesan kesalahan
    echo "Input data gagal";
}
?>