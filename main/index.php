<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Landing</title>
</head>
<body>
    <header class="fixed top-0 left-0 w-full h-[60px] z-20 bg-white/20 backdrop-blur-md text-white">
        <nav class="max-w-4xl mx-auto h-full flex items-center justify-between">
            <img src="../assets/Logo Mentaly.png" class="mr-1 h-12 sm:h-12" alt="Mie Me Logo" />
            <ul class="flex items-center justify-center gap-5 h-full">
                <li>
                    <a class="font-bold text-sky-300" href="index.php">Home</a>
                </li>
                <li>
                    <a class="font-bold hover:text-gray-100" href="main/about.php">Menu</a>
                </li>
                <li>
                    <a class="font-bold hover:text-gray-100" href="main/about.php">About</a>
                </li>
                <li>
                    <a class="font-bold hover:text-gray-100" href="main/about.php">Customer Service</a>
                </li>
            </ul>
            <a class="bg-blue-600 hover:bg-blue-900 px-4 py-2 rounded-xl text-white" href="register.php">Pesan Sekarang</a>
        </nav>
    </header>
    <?php include "../layout/section.html" ?>
    <?php include "../layout/menu.html" ?>
    <?php include "../layout/footer.html" ?>
</body>
</html>