<?php
include '../../service/database.php';

// Tambah / Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_menu = $_POST['kode_menu'];
    $nama_menu = $_POST['nama_menu'];
    $deskripsi_menu = $_POST['deskripsi_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $gambar_lama = $_POST['gambar_lama'];

    // Proses Upload Gambar
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_name = basename($_FILES['gambar']['name']);
        $gambar_target = "../../assets/img/menu/" . $gambar_name;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_target);
    } else {
        $gambar_name = $gambar_lama;
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

session_start();
if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Kelola Menu</title>
</head>
<body class="bg-gray-100 font-sans">
    
    <div class="flex">
        <?php include "sidebar.php"; ?>
        
        <div class="ml-64 flex-1 p-8">
            <div class="p-6 animate__animated animate__fadeIn">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Daftar Menu</h2>
                    <button onclick="document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 hover:scale-105 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Menu
                    </button>
                </div>

                <!-- Menampilkan menu -->
                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                    <?php
                    $result = $db->query("SELECT * FROM menu");
                    while ($row = $result->fetch_assoc()) {
                        $gambar_path = "../../assets/img/menu/" . $row["gambar"];
                        ?>
                    <li class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="relative overflow-hidden h-48">
                        <img src="<?php echo $gambar_path; ?>" alt="<?php echo $row["nama_menu"]; ?>" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4 text-left"> 
                            <h3 class="text-xl font-bold text-white"><?php echo $row["nama_menu"]; ?></h3>
                            <p class="text-blue-200 font-medium">Rp <?php echo number_format($row["harga"], 0, ',', '.'); ?></p>
                        </div>
                    </div>

                        <div class="p-4">
                            <p class="text-gray-500 text-sm mb-2 text-left"><?php echo $row["deskripsi"]; ?></p>
                            <div class="flex justify-between items-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full"><?php echo $row["kategori"]; ?></span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full"><?php echo $row["kode_menu"]; ?></span>
                            </div>
                            <div class="mt-4 flex justify-between w-full">
                                <button onclick="editMenu('<?php echo $row['kode_menu']; ?>', '<?php echo $row['nama_menu']; ?>', '<?php echo $row['kategori']; ?>', '<?php echo $row['harga']; ?>', '<?php echo $row['gambar']; ?>', '<?php echo addslashes($row['deskripsi']); ?>')" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white m-2 px-4 py-2 rounded-lg text-sm flex items-center justify-center w-1/2 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                                <a href="menu.php?delete=<?php echo $row['kode_menu']; ?>" 
                                    onclick="return confirmDelete('<?php echo $row['nama_menu']; ?>')"
                                    class="bg-red-500 hover:bg-red-600 text-white m-2 px-4 py-2 rounded-lg text-sm flex items-center justify-center w-1/2 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </a>
                            </div>

                        </div>
                    </li>
                    <?php } ?>
                </ul>
                <!-- End Menampilkan menu -->
                
                <!-- Form Tambah / Edit -->
                <div id="form-section" class="mt-12 bg-white p-6 rounded-xl shadow-lg border border-gray-200 animate__animated animate__fadeInUp">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah / Edit Menu</h2>
                    <form action="menu.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <input type="hidden" name="gambar_lama" id="gambar_lama">
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Kode Menu:</label>
                            <input type="text" name="kode_menu" id="kode_menu" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nama Menu:</label>
                            <input type="text" name="nama_menu" id="nama_menu" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                        </div>
                        
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Menu:</label>
                            <textarea name="deskripsi_menu" id="deskripsi_menu" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required></textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Kategori:</label>
                            <select name="kategori" id="kategori" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Harga:</label>
                            <input type="number" name="harga" id="harga" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                        </div>
                        
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Upload Gambar:</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <input type="file" name="gambar" id="gambar" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                                <div id="image-preview" class="hidden w-16 h-16 rounded-lg border border-gray-300 overflow-hidden">
                                    <img id="preview-img" src="" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 hover:scale-105 w-full md:w-auto">
                                Simpan Menu
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Form Tambah / Edit -->
            </div>
        </div>

    <script>
        function confirmDelete(menuName) {
            return confirm(`Apakah Anda yakin ingin menghapus menu "${menuName}"?`);
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
        
        // Preview image before upload
        document.getElementById('gambar').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.classList.remove('hidden');
                    previewImg.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>