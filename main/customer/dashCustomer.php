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
        if (!$db->query($update_sql)) {
            die("Error Update: " . $db->error);
        }
    } else {
        $insert_sql = "INSERT INTO chart (user_id, kode_menu, quantity) VALUES ('$user_id', '$kode_menu', $quantity)";
        if (!$db->query($insert_sql)) {
            die("Error Insert: " . $db->error);
        }
    }
    // Update cart count without reload
    echo "<script>updateCartCount();</script>";
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
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'bounce': 'bounce 1s infinite'
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
                    const cartCount = document.getElementById("cart-count");
                    cartCount.innerText = data.total_items;
                    cartCount.classList.add('animate-bounce');
                    setTimeout(() => cartCount.classList.remove('animate-bounce'), 1000);
                });
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('md:-translate-x-0');
        }
    </script>
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
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden group-hover:block animate-fade-in">
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
            <h1 class="text-3xl font-bold text-gray-900 mb-6 animate-slide-up">Selamat Datang, <?php echo htmlspecialchars($fullname); ?>!</h1>
            
            <?php 
            $categories = ['Makanan', 'Minuman', 'Dessert'];
            foreach ($categories as $category): ?>
                <section class="mb-12 animate-slide-up" style="animation-delay: <?php echo array_search($category, $categories) * 0.1 + 0.2 ?>s">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b-2 border-blue-100 pb-2 inline-block"><?php echo htmlspecialchars($category); ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($menus as $menu): ?>
                            <?php if ($menu['kategori'] === $category): ?>
                                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="../../assets/img/menu/<?php echo htmlspecialchars($menu['gambar']); ?>" 
                                            alt="<?php echo htmlspecialchars($menu['nama_menu']); ?>"
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                        <?php if ($menu['is_popular']): ?>
                                            <span class="absolute top-3 right-3 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm animate-bounce">
                                                Popular
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($menu['nama_menu']); ?></h3>
                                        <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($menu['deskripsi']); ?></p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-blue-600">Rp<?php echo number_format($menu['harga'], 0, ',', '.'); ?></span>
                                            <form action="dashCustomer.php" method="POST" class="flex items-center">
                                                <div class="flex items-center bg-gray-100 rounded-lg mr-3">
                                                    <button type="button" onclick="updateQuantity('<?php echo $menu['kode_menu']; ?>', 'decrease')" 
                                                            class="px-2 py-1 text-gray-600 hover:text-blue-600 transition-colors">
                                                        -
                                                    </button>
                                                    <input type="number" id="quantity_<?php echo $menu['kode_menu']; ?>" 
                                                           name="quantity" value="1" min="1" 
                                                           class="w-10 text-center border-none bg-transparent">
                                                    <button type="button" onclick="updateQuantity('<?php echo $menu['kode_menu']; ?>', 'increase')" 
                                                            class="px-2 py-1 text-gray-600 hover:text-blue-600 transition-colors">
                                                        +
                                                    </button>
                                                </div>
                                                <input type="hidden" name="kode_menu" value="<?php echo $menu['kode_menu']; ?>">
                                                <button type="submit" 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg flex items-center transition-colors">
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
</body>
</html>