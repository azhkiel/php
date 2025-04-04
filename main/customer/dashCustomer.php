<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$fullname = $_SESSION["fullname"];
$user_id = $_SESSION["user_id"];

// Get menu list
$sql = "SELECT * FROM menu";
$result = $db->query($sql);
$menus = $result->fetch_all(MYSQLI_ASSOC);

// Get cart item count
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
        $db->query($update_sql);
    } else {
        $insert_sql = "INSERT INTO chart (user_id, kode_menu, quantity) VALUES ('$user_id', '$kode_menu', $quantity)";
        $db->query($insert_sql);
    }
    
    // Get updated cart count
    $chart_result = $db->query($chart_sql);
    $chart_row = $chart_result->fetch_assoc();
    $total_items = $chart_row['total_items'] ?? 0;
    
    // Get menu name for notification
    $menu_sql = "SELECT nama_menu FROM menu WHERE kode_menu = '$kode_menu'";
    $menu_result = $db->query($menu_sql);
    $menu_row = $menu_result->fetch_assoc();
    $menu_name = $menu_row['nama_menu'] ?? '';
    
    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'total_items' => $total_items,
        'menu_name' => $menu_name,
        'quantity' => $quantity
    ]);
    exit();
}

if (isset($_GET['get_cart_count'])) {
    $chart_sql = "SELECT SUM(quantity) as total_items FROM chart WHERE user_id = '$user_id'";
    $chart_result = $db->query($chart_sql);
    $chart_row = $chart_result->fetch_assoc();
    $total_items = $chart_row['total_items'] ?? 0;
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'total_items' => $total_items]);
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            transform: translateX(120%);
            transition: transform 0.3s ease-out;
            background-color: #10B981;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
        }
        .notification.show {
            transform: translateX(0);
        }
        .notification-icon {
            margin-right: 0.5rem;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-pulse {
            animation: pulse 0.5s cubic-bezier(0.4, 0, 0.6, 1);
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="md:ml-64 transition-all duration-300">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <div class="flex items-center space-x-6">
                    <a href="chart.php" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                            <?php echo $total_items; ?>
                        </span>
                    </a>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($fullname); ?></span>
                            <svg class="h-4 w-4 text-gray-500 group-hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden group-hover:block">
                            <form method="post" action="dashCustomer.php">
                                <button type="submit" name="logout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Selamat Datang, <?php echo htmlspecialchars($fullname); ?>!</h1>
            
            <?php 
            $categories = ['Makanan', 'Minuman', 'Dessert'];
            foreach ($categories as $category): ?>
                <section class="mb-12">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b-2 border-blue-100 pb-2 inline-block"><?php echo htmlspecialchars($category); ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($menus as $menu): ?>
                            <?php if ($menu['kategori'] === $category): ?>
                                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="../../assets/img/menu/<?php echo htmlspecialchars($menu['gambar']); ?>" 
                                            alt="<?php echo htmlspecialchars($menu['nama_menu']); ?>"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($menu['nama_menu']); ?></h3>
                                        <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($menu['deskripsi']); ?></p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-blue-600">Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?></span>
                                            <form class="flex items-center add-to-cart-form">
                                                <div class="flex items-center bg-gray-100 rounded-lg mr-3">
                                                    <button type="button" class="decrease-btn px-2 py-1 text-gray-600 hover:text-blue-600 transition-colors">
                                                        -
                                                    </button>
                                                    <input type="number" name="quantity" value="1" min="1" 
                                                           class="quantity-input w-10 text-center border-none bg-transparent">
                                                    <button type="button" class="increase-btn px-2 py-1 text-gray-600 hover:text-blue-600 transition-colors">
                                                        +
                                                    </button>
                                                </div>
                                                <input type="hidden" name="kode_menu" value="<?php echo $menu['kode_menu']; ?>">
                                                <button type="submit" class="add-to-cart-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg flex items-center transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Add
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </main>
    </div>

    <!-- Notification Element -->
    <div id="notification" class="notification">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 notification-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span id="notification-text"></span>
    </div>

    <script>
        // Quantity controls
        document.querySelectorAll('.increase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                input.value = parseInt(input.value) + 1;
            });
        });

        document.querySelectorAll('.decrease-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });

        // Fungsi untuk update semua cart count di halaman
        function updateAllCartCounts(count) {
            // Update header cart count
            const headerCartCount = document.getElementById("cart-count");
            if (headerCartCount) {
                headerCartCount.textContent = count;
                headerCartCount.classList.add('animate-bounce');
                setTimeout(() => headerCartCount.classList.remove('animate-bounce'), 1000);
            }
            
            // Update sidebar cart count
            const sidebarCartCount = document.getElementById("sidebar-cart-count");
            if (sidebarCartCount) {
                sidebarCartCount.textContent = count;
                sidebarCartCount.classList.add('animate-pulse');
                setTimeout(() => sidebarCartCount.classList.remove('animate-pulse'), 1000);
            }
        }

        // Fungsi untuk get cart count dari server
        function fetchCartCount() {
            fetch("dashCustomer.php?get_cart_count=1")
                .then(response => response.json())
                .then(data => {
                    updateAllCartCounts(data.total_items);
                });
        }

        // Panggil saat pertama load
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartCount();
        });

        // Modifikasi fungsi addToCart
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('.add-to-cart-btn');
                const quantityInput = this.querySelector('.quantity-input');
                
                // Ubah state tombol
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Menambahkan...
                `;
                
                fetch('dashCustomer.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update semua cart count
                        updateAllCartCounts(data.total_items);
                        
                        // Tampilkan notifikasi
                        showNotification(data.menu_name, data.quantity);
                        
                        // Reset form
                        quantityInput.value = 1;
                        
                        // Kembalikan state tombol
                        setTimeout(() => {
                            submitBtn.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah
                            `;
                            submitBtn.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Gagal
                    `;
                    setTimeout(() => {
                        submitBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah
                        `;
                        submitBtn.disabled = false;
                    }, 2000);
                });
            });
        });

        // Show notification function
        function showNotification(menuName, quantity) {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');
            
            notificationText.textContent = `Added ${quantity}x ${menuName} to cart`;
            notification.classList.add('show');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('md:-translate-x-0');
        }
    </script>
</body>
</html>