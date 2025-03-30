<?php
// Menghubungkan ke database
include '../service/database.php';
?>

<?php
// Daftar kategori yang akan ditampilkan
$kategori_list = ['Makanan', 'Minuman', 'Dessert'];
echo "<h1 class='text-5xl text-center font-bold mt-6 mb-4'>Menu Menarik Kami</h1>";
echo "<h1 class='text-xl text-center text-gray-400 mt-6 mb-4'>Nikmati pilihan menu kami yang lezat dan dibuat dengan <br> bahan-bahan berkualitas tinggi</h1>";
// Melakukan loop untuk setiap kategori dalam daftar
foreach ($kategori_list as $kategori) {
    // Query untuk mengambil semua menu berdasarkan kategori, diurutkan berdasarkan nama_menu secara ascending
    $result = $db->query("SELECT * FROM menu WHERE kategori='$kategori' ORDER BY nama_menu ASC");

    // Jika ada data dalam kategori ini, maka ditampilkan
    if ($result->num_rows > 0) {
        // Menampilkan judul kategori dengan styling Tailwind CSS
        echo "<h1 class='text-2xl font-bold ml-4 mt-6 mb-4'>$kategori Pilihan</h1>";
        echo "<div class='p-2'>";
        
        // Membuat grid layout untuk menampilkan item dalam bentuk kartu
        echo "<ul class='grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8'>";

        // Loop untuk menampilkan setiap menu dalam kategori
        while ($row = $result->fetch_assoc()) {
            // Menentukan path gambar dari folder assets
            $gambar_path = "../assets/" . $row["gambar"];
            ?>

            <!-- Kartu menu dengan efek hover -->
            <li class="group rounded-lg shadow hover:shadow-lg hover:scale-105 transition p-0 flex flex-col overflow-hidden m-2">
                <!-- Container gambar agar tetap rapi -->
                <div class="w-full h-48 md:h-64 overflow-hidden">
                    <!-- Gambar menu dengan efek zoom saat hover -->
                    <img src="<?php echo $gambar_path; ?>" 
                        alt="<?php echo $row["nama_menu"]; ?>" 
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-125">
                </div>
                
                <!-- Bagian informasi menu -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Nama menu -->
                    <h3 class="text-2xl font-bold"><?php echo $row["nama_menu"]; ?></h3>

                    <!-- Harga menu dengan format rupiah -->
                    <p class="text-blue-600 font-bold pt-1 pb-1">
                        Rp <?php echo number_format($row["harga"], 0, ',', '.'); ?>
                    </p>

                    <!-- Deskripsi menu -->
                    <h5 class="text-gray-500 pb-3"><?php echo $row["deskripsi"]; ?></h5>

                    <!-- Tombol untuk memesan menu -->
                    <a href="" class="bg-blue-700 rounded-lg text-center mt-auto p-3 text-white w-full hover:scale-105 transition">
                        Ayo Pesan Sekarang!
                    </a>
                </div>
            </li>

            <?php 
        }
        echo "</ul>"; // Menutup grid ul
        echo "</div>"; // Menutup div kategori
    }
}
?>