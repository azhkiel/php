<?php
include 'koneksi.php';

// Mengambil data buku dari database
$query = "SELECT * FROM tb_buku ORDER BY id_buku ASC";
$result = mysqli_query($conn, $query);

$queryId = "SELECT MAX(id_buku) AS last_id FROM tb_buku";
$resultId = mysqli_query($conn, $queryId);
$row = mysqli_fetch_assoc($resultId);
$last_id = $row['last_id'] ?? 0;
$new_id = $last_id + 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
          crossorigin="anonymous">
    <script>
        function setDeleteId(id) {
            document.getElementById('deleteId').value = id;
        }
    </script>
</head>
<body>
    <div class="alert alert-success text-center" role="alert">
        <h2>DATA KOLEKSI BUKU PERPUSTAKAAN</h2>
    </div>
    
    <h1 class="ml-5">Tambah Koleksi Buku</h1>
    <form method="post" action="prosestambahbuku.php" class="ml-5">
        <div class="form-group row">
            <label for="id_buku" class="col-sm-1 col-form-label">ID Buku</label>
            <div class="col-sm-3">
                <input type="text" name="id_buku" class="form-control" value="<?php echo $new_id; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="judul" class="col-sm-1 col-form-label">Judul Buku</label>
            <div class="col-sm-3">
                <input type="text" name="judul" class="form-control" placeholder="Judul Buku">
            </div>
        </div>
        <div class="form-group row">
            <label for="pengarang" class="col-sm-1 col-form-label">Pengarang</label>
            <div class="col-sm-3">
                <input type="text" name="pengarang" class="form-control" placeholder="Pengarang">
            </div>
        </div>
        <div class="form-group row">
            <label for="tahun_terbit" class="col-sm-1 col-form-label">Tahun Terbit</label>
            <div class="col-sm-3">
                <input type="number" name="tahun_terbit" class="form-control" placeholder="Tahun Terbit">
            </div>
        </div>
        <div class="form-group row">
            <label for="kategori" class="col-sm-1 col-form-label">Kategori</label>
            <div class="col-sm-3">
                <input type="text" name="kategori" class="form-control" placeholder="Kategori">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-1 mt-1">Kirim</button>
        <a href="index.php" class="btn btn-primary mb-1 mt-1">Koleksi Buku</a>
    </form>
    
    <div class="container mt-5">
        <h2>Daftar Buku</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Tahun Terbit</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id_buku']; ?></td>
                        <td><?php echo $row['judul_buku']; ?></td>
                        <td><?php echo $row['pengarang']; ?></td>
                        <td><?php echo $row['tahun_terbit']; ?></td>
                        <td><?php echo $row['kategori']; ?></td>
                        <td>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteId(<?php echo $row['id_buku']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Bootstrap untuk Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus buku ini?
                </div>
                <div class="modal-footer">
                    <form method="post" action="deletebuku.php">
                        <input type="hidden" name="id_buku" id="deleteId">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
