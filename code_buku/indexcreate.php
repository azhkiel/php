<?php 
// Menghubungkan ke database
include_once ("koneksi.php");

// Query untuk mengambil semua data dari tabel tb_buku
$query = "SELECT * FROM tb_buku";
$hasil = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Koleksi Buku</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
          crossorigin="anonymous">
</head>
<body>
    <!-- Header -->
    <div class="alert alert-success text-center" role="alert">
        <h2>DATA KOLEKSI BUKU PERPUSTAKAAN</h2>
    </div>

    <!-- Tombol Tambah Buku -->
    <a href="tambahbuku.php" class="btn btn-primary mb-1 mt-1">
        <i class="fas fa-user-plus mr-2"></i>Tambah Buku
    </a>

    <!-- Tabel Koleksi Buku -->
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Id_Buku</th>
                <th scope="col">Judul Buku</th>
                <th scope="col">Pengarang</th>
                <th scope="col">Tahun Terbit</th>
                <th scope="col">Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($data = mysqli_fetch_array($hasil)) { ?>
                <tr>
                    <th scope="row"> <?php echo $data['id_buku']; ?> </th>
                    <td> <?php echo $data['judul_buku']; ?> </td>
                    <td> <?php echo $data['pengarang']; ?> </td>
                    <td> <?php echo $data['tahun_terbit']; ?> </td>
                    <td> <?php echo $data['kategori']; ?> </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" 
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" 
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" 
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" 
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
            crossorigin="anonymous"></script>
</body>
</html>