<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$chart_sql = "SELECT SUM(quantity) as total_items FROM chart WHERE user_id = '$user_id'";
$chart_result = $db->query($chart_sql);
$chart_row = $chart_result->fetch_assoc();
$total_items = $chart_row['total_items'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-100">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Pesanan Saya</h2>
        <div class="flex items-center space-x-4">
        <a href="chart.php" class="relative text-blue-600">
            🛒
            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                <?php echo $total_items; ?>
            </span>
        </a>
            <a href="dashCustomer.php" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">back</a>
        </div>
    </div>
    
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
                            if (nextStatus) {
                                updateStatus(nextStatus, null, delay);
                            } else {
                                location.reload();
                            }
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }, delay);
        }

        updateStatus("pending", "processed", 10000);
        updateStatus("processed", "completed", 20000);

        function updateCartCount() {
            fetch('get_chart_count.php')
                .then(response => response.json())
                .then(data => {
                    let countElement = document.getElementById('cart-count');
                    if (data.count > 0) {
                        countElement.textContent = data.count;
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        updateCartCount();
    </script>
</body>
</html>
