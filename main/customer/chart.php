<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data dari tabel chart
$query = "SELECT chart.id, menu.kode_menu, menu.nama_menu, menu.harga, menu.gambar, chart.quantity FROM chart JOIN menu ON chart.kode_menu = menu.kode_menu WHERE chart.user_id = '$user_id'";
$result = $db->query($query);

$subtotal = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $row['total_harga'] = $row['harga'] * $row['quantity'];
    $subtotal += $row['total_harga'];
    $items[] = $row;
}

// Proses checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    if ($subtotal > 0) {
        $order_sql = "INSERT INTO `order` (user_id, total_price) VALUES ('$user_id', '$subtotal')";
        if ($db->query($order_sql)) {
            $order_id = $db->insert_id;
            
            foreach ($items as $item) {
                $kode_menu = $item["kode_menu"];
                $quantity = $item["quantity"];
                $price = $item["harga"];
                $order_details_sql = "INSERT INTO `order_details` (order_id, kode_menu, quantity, price) VALUES ('$order_id', '$kode_menu', '$quantity', '$price')";
                $db->query($order_details_sql);
            }
            
            // Hapus data di chart setelah pesanan diproses
            $db->query("DELETE FROM chart WHERE user_id = '$user_id'");
            header("Location: dashCustomer.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../src/sty.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-2">Keranjang Belanja</h1>
        <p class="text-gray-600 mb-4"><?php echo count($items); ?> item dalam keranjang</p>

        <div class="flex">
            <!-- Daftar item dalam keranjang -->
            <div class="w-2/3 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold mb-4">Item dalam Keranjang</h2>
                <?php foreach ($items as $item): ?>
                    <div class="flex items-center border-b py-4">
                        <img src="../../assets/<?php echo $item['gambar']; ?>" class="w-16 h-16 rounded-md mr-4">
                        <div class="flex-1">
                            <p class="font-semibold"><?php echo $item['nama_menu']; ?></p>
                            <p class="text-gray-600">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="flex items-center">
                            <a href="update_chart.php?action=decrease&id=<?php echo $item['id']; ?>" class="px-2 py-1 bg-gray-300 text-gray-700 rounded">-</a>
                            <span class="mx-2"><?php echo $item['quantity']; ?></span>
                            <a href="update_chart.php?action=increase&id=<?php echo $item['id']; ?>" class="px-2 py-1 bg-gray-300 text-gray-700 rounded">+</a>
                        </div>
                        <p class="w-20 text-right font-semibold">Rp <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></p>
                        <a href="update_chart.php?action=remove&id=<?php echo $item['id']; ?>" class="ml-4 text-red-600">🗑</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="w-1/3 ml-6 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold mb-4">Ringkasan Pesanan</h2>
                <div class="flex justify-between mb-2">
                    <p>Subtotal</p>
                    <p>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                </div>
                <hr class="my-2">
                <div class="flex justify-between font-bold text-lg">
                    <p>Total</p>
                    <p>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                </div>
                <form method="post">
                    <button type="submit" name="checkout" class="w-full bg-red-600 text-white py-2 mt-4 rounded-lg font-semibold">Checkout Sekarang</button>
                </form>
                <button onclick="window.location.href='dashCustomer.php'" class="w-full bg-gray-200 text-gray-700 py-2 mt-2 rounded-lg">Lanjutkan Belanja</button>
            </div>
        </div>
    </div>
</body>
</html>