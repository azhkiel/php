<?php
session_start();
include "../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$fullname = $_SESSION["fullname"];
$user_id = $_SESSION["user_id"]; // Pastikan user_id tersimpan saat login

// Ambil daftar menu dari tabel mieme
$sql = "SELECT * FROM mieme";
$result = $db->query($sql);
$menus = $result->fetch_all(MYSQLI_ASSOC);
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
    
    <h2 class="text-xl font-semibold mb-2">Daftar Menu</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($menus as $menu): ?>
            <div class="bg-white p-4 rounded shadow-md">
                <h3 class="text-lg font-bold"><?php echo htmlspecialchars($menu['name']); ?></h3>
                <p class="text-gray-600">Harga: Rp<?php echo number_format($menu['price'], 0, ',', '.'); ?></p>
                <form action="chart.php" method="POST">
                    <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                    <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Add to Chart
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="chart.php" class="mt-4 block text-blue-600 hover:underline">Lihat Chart</a>
    <a href="../logout.php" class="mt-2 block text-red-600 hover:underline">Logout</a>
</body>
</html>