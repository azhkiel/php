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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kode_menu"])) {
    $kode_menu = $_POST["kode_menu"];
    
    // Gunakan prepared statements untuk keamanan
    $check_sql = "SELECT * FROM chart WHERE user_id = ? AND kode_menu = ?";
    $stmt = $db->prepare($check_sql);
    $stmt->bind_param("is", $user_id, $kode_menu);
    $stmt->execute();
    $check_result = $stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE chart SET quantity = quantity + 1 WHERE user_id = ? AND kode_menu = ?";
        $stmt = $db->prepare($update_sql);
        $stmt->bind_param("is", $user_id, $kode_menu);
    } else {
        $insert_sql = "INSERT INTO chart (user_id, kode_menu, quantity) VALUES (?, ?, 1)";
        $stmt = $db->prepare($insert_sql);
        $stmt->bind_param("is", $user_id, $kode_menu);
    }
    
    if (!$stmt->execute()) {
        die("Error Query: " . $stmt->error);
    }
}

if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
    <a href="chart.php" class="mt-4 block text-blue-600 hover:underline">Lihat Chart</a>
    <h2 class="text-xl font-semibold mb-2">Daftar Menu</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($menus as $menu): ?>
            <div class="bg-white p-4 rounded shadow-md">
                <img src="../../assets/<?php echo htmlspecialchars($menu['gambar']); ?>" class="w-full h-40 object-cover mb-2 rounded">
                <h3 class="text-lg font-bold"><?php echo htmlspecialchars($menu['nama_menu']); ?></h3>
                <p class="text-gray-600">Kategori: <?php echo htmlspecialchars($menu['kategori']); ?></p>
                <p class="text-gray-600">Harga: Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?></p>
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($menu['deskripsi']); ?></p>
                <form action="dashCustomer.php" method="POST">
                    <input type="hidden" name="kode_menu" value="<?php echo $menu['kode_menu']; ?>">
                    <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Add to Chart
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <h2 class="text-xl font-semibold mt-6 mb-2">Pesanan Saya</h2>
    <table class="w-full bg-white rounded shadow-md">
        <thead>
            <tr>
                <th class="p-2">ID Pesanan</th>
                <th class="p-2">Total Harga</th>
                <th class="p-2">Status</th>
                <th class="p-2">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $order_sql = "SELECT * FROM `order` WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $db->prepare($order_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $orders = $stmt->get_result();
            while ($order = $orders->fetch_assoc()):
            ?>
                <tr>
                    <td class="p-2"><?php echo $order["id"]; ?></td>
                    <td class="p-2">Rp<?php echo number_format($order["total_price"], 0, ',', '.'); ?></td>
                    <td class="p-2"><?php echo ucfirst($order["status"]); ?></td>
                    <td class="p-2"><?php echo $order["created_at"]; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <script>
        function updateStatus(currentStatus, nextStatus, delay) {
            setTimeout(() => {
                fetch(`update_status.php?status=${currentStatus}`)
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        if (data.includes("updated")) {
                            // Jika status berhasil diperbarui, lanjut ke tahap berikutnya
                            if (nextStatus) {
                                updateStatus(nextStatus, null, delay);
                            } else {
                                location.reload(); // Reload hanya jika sudah sampai tahap akhir
                            }
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }, delay);
        }

        // Jalankan perubahan status bertahap
        updateStatus("pending", "processed", 10000);
        updateStatus("processed", "completed", 20000);
        </script>


    <form method="post" action="dashCustomer.php" class="mt-6">
        <button type="submit" name="logout" 
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
            Log Out
        </button>
    </form>
</body>
</html>