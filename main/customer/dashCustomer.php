<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$fullname = $_SESSION["fullname"];
$user_id = $_SESSION["user_id"];

// Ambil daftar menu dari tabel menu
$sql = "SELECT * FROM menu";
$result = $db->query($sql);
$menus = $result->fetch_all(MYSQLI_ASSOC);

// Ambil jumlah item di chart
$chart_sql = "SELECT SUM(quantity) as total_items FROM chart WHERE user_id = '$user_id'";
$chart_result = $db->query($chart_sql);
$chart_row = $chart_result->fetch_assoc();
$total_items = $chart_row['total_items'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kode_menu"])) {
    $kode_menu = $_POST["kode_menu"];
    $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;
    
    $check_sql = "SELECT * FROM chart WHERE user_id = '$user_id' AND kode_menu = '$kode_menu'";
    $check_result = $db->query($check_sql);

    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE chart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND kode_menu = '$kode_menu'";
        if (!$db->query($update_sql)) {
            die("Error Update: " . $db->error);
        }
    } else {
        $insert_sql = "INSERT INTO chart (user_id, kode_menu, quantity) VALUES ('$user_id', '$kode_menu', $quantity)";
        if (!$db->query($insert_sql)) {
            die("Error Insert: " . $db->error);
        }
    }
}

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
    <title>Dashboard Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function updateQuantity(id, type) {
            let input = document.getElementById('quantity_' + id);
            let value = parseInt(input.value);
            if (type === 'increase') {
                input.value = value + 1;
            } else if (type === 'decrease' && value > 1) {
                input.value = value - 1;
            }
        }

        function updateCartCount() {
            fetch("get_cart_count.php")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("cart-count").innerText = data.total_items;
                });
        }
    </script>
</head>
<body class="p-8 bg-gray-100">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
        <div class="flex items-center space-x-4">
            <a href="chart.php" class="relative text-blue-600 text-2xl">
                🛒
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    <?php echo $total_items; ?>
                </span>
            </a>
            <a href="pesanan.php" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Pesanan Saya</a>
        </div>
    </div>
    
    <h2 class="text-xl font-semibold mb-2">Daftar Menu</h2>
    
    <?php 
    $kategori = ['Makanan', 'Minuman', 'Dessert'];
    foreach ($kategori as $kat): ?>
        <h2 class="text-xl font-bold mt-4 mb-2"><?php echo htmlspecialchars($kat); ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($menus as $menu): ?>
                <?php if ($menu['kategori'] === $kat): ?>
                    <div class="menu-item bg-white p-4 rounded-xl shadow-md flex flex-col border border-gray-200">
                        <div class="relative w-full h-48 md:h-64 overflow-hidden">
                            <img src="../../assets/<?php echo htmlspecialchars($menu['gambar']); ?>" class="w-full h-full object-cover rounded-t-xl transition-transform duration-300 hover:scale-125">
                            <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Popular</span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($menu['nama_menu']); ?></h3>
                            <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($menu['deskripsi']); ?></p>
                            <div class="flex justify-between items-center mt-3">
                                <p class="text-xl font-bold text-blue-500">Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?></p>
                                <div class="flex items-center bg-gray-200 rounded-lg px-2 py-1">
                                    <button type="button" onclick="updateQuantity('<?php echo $menu['kode_menu']; ?>', 'decrease')" class="text-gray-600 px-2">-</button>
                                    <input type="number" id="quantity_<?php echo $menu['kode_menu']; ?>" name="quantity" value="1" min="1" class="w-10 text-center border-none bg-transparent">
                                    <button type="button" onclick="updateQuantity('<?php echo $menu['kode_menu']; ?>', 'increase')" class="text-gray-600 px-2">+</button>
                                </div>
                            </div>
                            <form action="dashCustomer.php" method="POST" class="mt-3 w-full">
                                <input type="hidden" name="kode_menu" value="<?php echo $menu['kode_menu']; ?>">
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                    🛒 Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <form method="post" action="dashCustomer.php" class="mt-6">
        <button type="submit" name="logout" 
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
            Log Out
        </button>
    </form>
</body>
</html>
