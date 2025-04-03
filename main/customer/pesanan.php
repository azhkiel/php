<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$fullname = $_SESSION["fullname"];

// Get cart count for sidebar
$chart_sql = "SELECT SUM(quantity) as total_items FROM chart WHERE user_id = '$user_id'";
$chart_result = $db->query($chart_sql);
$chart_row = $chart_result->fetch_assoc();
$total_items = $chart_row['total_items'] ?? 0;

// Get orders
$order_sql = "SELECT * FROM `order` WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $db->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - MieMe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }

        function updateCartCount() {
            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const sidebarCartCount = document.getElementById('sidebar-cart-count');
                    const headerCartCount = document.getElementById('header-cart-count');
                    
                    if (sidebarCartCount) {
                        sidebarCartCount.textContent = data.count || 0;
                    }
                    
                    if (headerCartCount) {
                        headerCartCount.textContent = data.count || 0;
                        if (data.count > 0) {
                            headerCartCount.classList.remove('hidden');
                            headerCartCount.classList.add('animate-pulse-slow');
                            setTimeout(() => headerCartCount.classList.remove('animate-pulse-slow'), 3000);
                        } else {
                            headerCartCount.classList.add('hidden');
                        }
                    }
                });
        }

        function simulateStatusUpdates() {
            const statusElements = document.querySelectorAll('.status-badge');
            
            statusElements.forEach(element => {
                const currentStatus = element.dataset.status;
                const orderId = element.dataset.orderId;
                
                if (currentStatus === 'pending') {
                    setTimeout(() => {
                        updateStatus(orderId, 'processed');
                    }, 10000);
                } else if (currentStatus === 'processed') {
                    setTimeout(() => {
                        updateStatus(orderId, 'completed');
                    }, 20000);
                }
            });
        }

        function updateStatus(orderId, newStatus) {
            fetch(`update_status.php?order_id=${orderId}&status=${newStatus}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusElement = document.querySelector(`.status-badge[data-order-id="${orderId}"]`);
                        if (statusElement) {
                            statusElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                            statusElement.dataset.status = newStatus;
                            updateStatusBadgeStyle(statusElement, newStatus);
                            
                            // Add animation
                            statusElement.classList.add('animate-pulse-slow');
                            setTimeout(() => statusElement.classList.remove('animate-pulse-slow'), 3000);
                        }
                    }
                });
        }

        function updateStatusBadgeStyle(element, status) {
            // Reset classes
            element.className = 'status-badge px-3 py-1 rounded-full text-xs font-semibold';
            
            // Add status-specific classes
            switch(status) {
                case 'pending':
                    element.classList.add('bg-yellow-100', 'text-yellow-800');
                    break;
                case 'processed':
                    element.classList.add('bg-blue-100', 'text-blue-800');
                    break;
                case 'completed':
                    element.classList.add('bg-green-100', 'text-green-800');
                    break;
                case 'cancelled':
                    element.classList.add('bg-red-100', 'text-red-800');
                    break;
                default:
                    element.classList.add('bg-gray-100', 'text-gray-800');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            simulateStatusUpdates();
        });
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?> 
    <!-- Main Content -->
    <div class="md:ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10 animate-slide-up">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Pesanan Saya</h1>
                <div class="flex items-center space-x-4">
                    <a href="chart.php" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="header-cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full <?= $total_items > 0 ? '' : 'hidden' ?>">
                            <?= $total_items ?>
                        </span>
                    </a>
                    <a href="dashCustomer.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <!-- Order Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <?php if ($orders->num_rows > 0): ?>
                <div class="grid grid-cols-1 gap-6">
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 animate-fade-in">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
                                    <div class="mb-4 md:mb-0">
                                        <h3 class="text-lg font-bold text-gray-800">Pesanan #<?= $order['id'] ?></h3>
                                        <p class="text-sm text-gray-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-lg font-bold text-blue-600">Rp<?= number_format($order['total_price'], 0, ',', '.') ?></span>
                                        <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold 
                                            <?= $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                            <?= $order['status'] === 'processed' ? 'bg-blue-100 text-blue-800' : '' ?>
                                            <?= $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : '' ?>
                                            <?= $order['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : '' ?>"
                                            data-status="<?= $order['status'] ?>"
                                            data-order-id="<?= $order['id'] ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Order Items -->
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="font-medium text-gray-700 mb-3">Item Pesanan</h4>
                                    <?php 
                                    $items_sql = "SELECT m.nama_menu, m.harga, od.quantity 
                                                FROM order_details od 
                                                JOIN menu m ON od.kode_menu = m.kode_menu 
                                                WHERE od.order_id = ?";
                                    $items_stmt = $db->prepare($items_sql);
                                    $items_stmt->bind_param("i", $order['id']);
                                    $items_stmt->execute();
                                    $items = $items_stmt->get_result();
                                    
                                    while ($item = $items->fetch_assoc()): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-100 last:border-0">
                                            <div class="flex items-center">
                                                <span class="text-gray-700"><?= $item['nama_menu'] ?></span>
                                                <span class="text-xs text-gray-500 ml-2">x<?= $item['quantity'] ?></span>
                                            </div>
                                            <span class="text-gray-600">Rp<?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></span>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                
                                <!-- Order Actions -->
                                <?php if ($order['status'] === 'pending'): ?>
                                    <div class="flex justify-end space-x-3 mt-4">
                                        <button class="text-sm text-red-600 hover:text-red-800 font-medium px-3 py-1 rounded border border-red-200 hover:bg-red-50 transition-colors">
                                            Batalkan Pesanan
                                        </button>
                                        <button class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 rounded transition-colors">
                                            Hubungi Kami
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 animate-fade-in">
                    <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Pesanan Anda akan muncul di sini setelah melakukan pembelian</p>
                    <a href="dashCustomer.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                        Lihat Menu
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script>
    // Function to update status with delays
    function simulateStatusUpdates() {
        const statusElements = document.querySelectorAll('.status-badge');
        
        statusElements.forEach(element => {
            const currentStatus = element.dataset.status;
            const orderId = element.dataset.orderId;
            
            if (currentStatus === 'pending') {
                // Update to processed after 5 seconds
                setTimeout(() => {
                    updateStatus(orderId, 'processed');
                    
                    // Then update to completed after another 10 seconds (15 seconds total from start)
                    setTimeout(() => {
                        updateStatus(orderId, 'completed');
                    }, 10000);
                    
                }, 5000);
            }
        });
    }

    // Function to update status via AJAX
    function updateStatus(orderId, newStatus) {
        fetch(`update_status.php?order_id=${orderId}&status=${newStatus}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusElement = document.querySelector(`.status-badge[data-order-id="${orderId}"]`);
                    if (statusElement) {
                        statusElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        statusElement.dataset.status = newStatus;
                        updateStatusBadgeStyle(statusElement, newStatus);
                        
                        // Add animation
                        statusElement.classList.add('animate-pulse-slow');
                        setTimeout(() => statusElement.classList.remove('animate-pulse-slow'), 3000);
                    }
                }
            });
    }

    // Function to update badge style based on status
    function updateStatusBadgeStyle(element, status) {
        // Reset classes
        element.className = 'status-badge px-3 py-1 rounded-full text-xs font-semibold';
        
        // Add status-specific classes
        switch(status) {
            case 'pending':
                element.classList.add('bg-yellow-100', 'text-yellow-800');
                break;
            case 'processed':
                element.classList.add('bg-blue-100', 'text-blue-800');
                break;
            case 'completed':
                element.classList.add('bg-green-100', 'text-green-800');
                break;
            case 'cancelled':
                element.classList.add('bg-red-100', 'text-red-800');
                break;
            default:
                element.classList.add('bg-gray-100', 'text-gray-800');
        }
    }

    // Start the status updates when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        simulateStatusUpdates();
    });
</script>
</body>
</html>