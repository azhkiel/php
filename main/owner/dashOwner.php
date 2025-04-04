<?php
include "../../service/database.php";
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
    <title>Owner Dashboard</title>
</head>
<body class="bg-gray-100 font-sans">
    
    <div class="flex">
        <?php include "sidebar.php"; ?>
        
        <!-- Main Content -->
        <div class="ml-64 flex-1 p-8">
            <section class="flex flex-col items-center justify-center">
                <!-- Welcome Card -->
                <div class="bg-white/90 p-8 rounded-xl shadow-2xl backdrop-blur-md w-full max-w-screen-lg text-center transform transition-all duration-300 hover:scale-[1.01]">
                    <h1 class="text-4xl font-bold mb-4 text-gray-800 animate__animated animate__fadeInDown">
                        Selamat Datang, <span class="text-blue-600"><?= $_SESSION["username"] ?></span>!
                    </h1>
                    <p class="text-gray-600 mb-6 animate__animated animate__fadeIn animate__delay-1s">
                        Silakan pilih menu navigasi di sidebar untuk mengelola sistem
                    </p>
                    
                    <!-- Quick Stats (optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <?php
                        $menu_count = $db->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'];
                        ?>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <h3 class="text-blue-800 font-medium">Total Menu</h3>
                            <p class="text-3xl font-bold text-blue-600"><?= $menu_count ?></p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <h3 class="text-green-800 font-medium">Kategori Menu</h3>
                            <p class="text-3xl font-bold text-green-600">3</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                            <h3 class="text-purple-800 font-medium">Aktivitas</h3>
                            <p class="text-3xl font-bold text-purple-600">-</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>