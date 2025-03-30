<header class="fixed top-0 left-0 w-full h-[80px] z-20 bg-white border border-b-2">
    <nav>
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../assets/Logo Mentaly.png" class="h-12" alt="Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">MieMe</span>
            </a>
            <div class="flex items-center md:order-2 space-x-1 md:space-x-2 rtl:space-x-reverse">
                <a href="login.php" class=" border border-blue-700 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 focus:outline-none">Masuk</a>
                <a href="register.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 focus:outline-none">Daftar</a>
                <button data-collapse-toggle="mega-menu" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-black rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="mega-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div id="mega-menu" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
                <ul class="flex flex-col mt-4 font-bold md:flex-row md:mt-0 md:space-x-8 rtl:space-x-reverse bg-white">
                    <li>
                        <a href="index.php" class="block py-2 px-3 text-blue-600 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0" aria-current="page">Beranda</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0">Menu</a>
                    </li>
                    <li>
                        <a href="#" onclick="loadSection('promo'); return false;" class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0">
                            Promo
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="loadSection('about'); return false;" class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
</header>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const menuButton = document.querySelector("[data-collapse-toggle='mega-menu']");
    const megaMenu = document.getElementById("mega-menu");

    if (menuButton && megaMenu) {
        menuButton.addEventListener("click", function () {
            megaMenu.classList.toggle("hidden");
        });
    }
});
</script>

