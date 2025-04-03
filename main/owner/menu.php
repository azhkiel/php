<?php
include '../../service/database.php';

// Tambah / Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_menu = $_POST['kode_menu'];
    $nama_menu = $_POST['nama_menu'];
    $deskripsi_menu = $_POST['deskripsi_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $gambar_lama = $_POST['gambar_lama']; // Untuk update gambar

    // Proses Upload Gambar
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_name = basename($_FILES['gambar']['name']);
        $gambar_target = "../../assets/" . $gambar_name;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_target);
    } else {
        $gambar_name = $gambar_lama; // Jika tidak upload gambar, pakai yang lama
    }

    // Cek apakah ini edit atau tambah baru
    if (isset($_POST['edit_id']) && $_POST['edit_id'] != "") {
        $edit_id = $_POST['edit_id'];
        $sql = "UPDATE menu SET kode_menu='$kode_menu', nama_menu='$nama_menu', kategori='$kategori', harga='$harga', gambar='$gambar_name', deskripsi='$deskripsi_menu' WHERE kode_menu='$edit_id'";
    } else {
        $sql = "INSERT INTO menu (kode_menu, nama_menu, kategori, harga, gambar, deskripsi) VALUES ('$kode_menu', '$nama_menu', '$kategori', '$harga', '$gambar_name','$deskripsi_menu')";
    }

    if ($db->query($sql)) {
        header("Location: dashOwner.php");
    } else {
        echo "Error: " . $db->error;
    }
}

// Hapus Data
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $db->query("DELETE FROM menu WHERE kode_menu='$delete_id'");
    header("Location: dashOwner.php");
}
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Menu</h2>

    <button onclick="document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });"
        class="bg-green-600 text-white px-4 py-2 rounded mb-4">
        + Tambah Menu
    </button>

    <!-- Menampilkan menu -->
    <ul class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $result = $db->query("SELECT * FROM menu");
        while ($row = $result->fetch_assoc()) {
            $gambar_path = "../../assets/" . $row["gambar"];
            ?>
        <li class="border rounded-lg p-4 shadow hover:shadow-lg transition">
            <img src="<?php echo $gambar_path; ?>" alt="<?php echo $row["nama_menu"]; ?>" class="w-full h-32 object-cover rounded mb-2">
            <h3 class="text-lg font-semibold"><?php echo $row["nama_menu"]; ?></h3>
            <p class="text-sm"><?php echo $row["kode_menu"]; ?></p>
            <p class="text-gray-500 text-sm"><?php echo $row["deskripsi"]; ?></p>
            <p class="text-gray-500 text-sm"><?php echo $row["kategori"]; ?></p>
            <p class="text-blue-600 font-bold">Rp <?php echo number_format($row["harga"], 0, ',', '.'); ?></p>
            <div class="mt-2">
    <button onclick="editMenu('<?php echo $row['kode_menu']; ?>', '<?php echo $row['nama_menu']; ?>', '<?php echo $row['kategori']; ?>', '<?php echo $row['harga']; ?>', '<?php echo $row['gambar']; ?>')" 
        class="bg-yellow-500 text-white px-2 py-1 rounded">
        Edit
    </button>

    <a href="menu.php?delete=<?php echo $row['kode_menu']; ?>" 
        onclick="return confirmDelete('<?php echo $row['nama_menu']; ?>')"
        class="bg-red-500 text-white px-2 py-1 rounded">
        Hapus
    </a>
</div>
        </li>
        <?php } ?>
    </ul>
    <!-- End Menampilkan menu -->
    
    <!-- Form Tambah / Edit -->
    <div id="form-section" class="mt-10 bg-gray-100 p-4 rounded">
        <h2 class="text-xl font-bold mb-4">Tambah / Edit Menu</h2>
        <form action="menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" id="edit_id">
            <input type="hidden" name="gambar_lama" id="gambar_lama">
            
            <label class="block mb-2">Kode Menu:</label>
            <input type="text" name="kode_menu" id="kode_menu" class="w-full p-2 border rounded mb-2" required>
            
            <label class="block mb-2">Nama Menu:</label>
            <input type="text" name="nama_menu" id="nama_menu" class="w-full p-2 border rounded mb-2" required>
            
            <label class="block mb-2">Deskripsi Menu:</label>
            <input type="text" name="deskripsi_menu" id="deskripsi_menu" class="w-full p-2 border rounded mb-2" required>
            
            <label class="block mb-2">Kategori:</label>
            <select name="kategori" id="kategori" class="w-full p-2 border rounded mb-2" required>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Dessert">Dessert</option>
            </select>
            
            <label class="block mb-2">Harga:</label>
            <input type="number" name="harga" id="harga" class="w-full p-2 border rounded mb-2" required>
            
            <label class="block mb-2">Upload Gambar:</label>
            <input type="file" name="gambar" id="gambar" class="w-full p-2 border rounded mb-2">
            
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
    <!-- Form Tambah / Edit -->
</div>

<script>
    function confirmDelete(menuName) {
    return confirm("Apakah Anda yakin ingin menghapus menu '" + menuName + "'?");
}
    function editMenu(kode, nama, kategori, harga, gambar, deskripsi) {
        document.getElementById('edit_id').value = kode;
        document.getElementById('kode_menu').value = kode;
        document.getElementById('nama_menu').value = nama;
        document.getElementById('deskripsi_menu').value = deskripsi;
        document.getElementById('kategori').value = kategori;
        document.getElementById('harga').value = harga;
        document.getElementById('gambar_lama').value = gambar;
        
        document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });
    }
</script>
