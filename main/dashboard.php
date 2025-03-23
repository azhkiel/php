<?php
session_start();
if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="bg-gray-100">
    <?php include "../layout/header.html"?>
    <section class="relative min-h-screen flex flex-col items-center justify-center">
        <!-- Background Image -->
        <img src="../assets/background.jpg" class="w-full h-full object-cover absolute top-0 left-0 z-0">

        <!-- Dashboard Container -->
        <div class="relative z-10 bg-white/80 p-8 rounded-lg shadow-lg backdrop-blur-md w-full max-w-screen-lg text-center">
            <h1 class="text-3xl font-bold mb-4">
                Selamat Datang, Tn. <?= $_SESSION["username"] ?>!
            </h1>

            <!-- Include Menu -->
            <div class="mt-6 w-full">
                <?php include 'menu.php'; ?>
            </div>

            <!-- Logout Button -->
            <form method="post" action="dashboard.php" class="mt-6">
                <button type="submit" name="logout" 
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Log Out
                </button>
            </form>
        </div>
    </section>

    <?php include "../layout/footer.html"?>
</body>
</html>