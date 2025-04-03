<!-- sidebar.php -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 -translate-x-full md:translate-x-0 bg-white shadow-lg transition-transform duration-300 ease-in-out">
    <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
        <div class="flex items-center">
            <img src="../../assets/img/mieme/Logo Mentaly.png" class="h-10 mr-2" alt="Logo">
            <span class="text-xl font-bold text-white">MieMe</span>
        </div>
    </div>
    
    <div class="overflow-y-auto h-full py-4 px-3">
        <ul class="space-y-2">
            <li>
                <a href="dashCustomer.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="menu.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="ml-3">Menu</span>
                </a>
            </li>
            <li>
                <a href="pesanan.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="ml-3">Pesanan Saya</span>
                </a>
            </li>
            <li>
                <a href="chart.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="ml-3">Keranjang</span>
                    <span id="sidebar-cart-count" class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">
                        <?php echo $total_items; ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="promo.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                    </svg>
                    <span class="ml-3">Promo</span>
                </a>
            </li>
            <li>
                <form method="post" action="dashCustomer.php" class="w-full">
                    <button type="submit" name="logout" class="flex items-center w-full p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>