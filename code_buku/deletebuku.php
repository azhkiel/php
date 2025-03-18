<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku = $_POST['id_buku'];
    
    // Query untuk menghapus data berdasarkan ID
    $query = "DELETE FROM tb_buku WHERE id_buku = '$id_buku'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Buku berhasil dihapus!'); window.location.href='tambahbuku.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku!'); window.location.href='tambahbuku.php';</script>";
    }
}
?>
