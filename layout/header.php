<header class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md shadow-sm transition-all duration-300 hover:shadow-md">
    <nav>
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto p-4">
            <!-- Logo with Animation -->
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse group">
                <img src="../assets/img/mieme/Logo Mentaly.png" class="h-12 transition-transform duration-300 group-hover:rotate-12" alt="MieMe Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                    MieMe
                </span>
            </a>

            <!-- Auth Buttons with Animation -->
            <div class="flex items-center md:order-2 space-x-2 md:space-x-3 rtl:space-x-reverse">
                <a href="login.php" 
                   class="relative overflow-hidden border border-blue-600 text-blue-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 focus:outline-none transition-all duration-300 group">
                   <span class="relative z-10">Masuk</span>
                   <span class="absolute inset-0 bg-blue-600 origin-left transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 z-0"></span>
                </a>
                <a href="register.php" 
                   class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 focus:outline-none transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                   <span class="relative z-10">Daftar</span>
                   <span class="absolute inset-0 bg-white/10 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </a>
                
                <!-- Mobile Menu Button -->
                <button data-collapse-toggle="mega-menu" type="button" 
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-800 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300"
                        aria-controls="mega-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <div id="mega-menu" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
                <ul class="flex flex-col mt-4 font-medium md:flex-row md:mt-0 md:space-x-6 rtl:space-x-reverse">
                    <li>
                        <a href="index.php" 
                           class="nav-link block py-2 px-3 text-blue-600 md:p-0 relative group"
                           aria-current="page">
                           <span>Beranda</span>
                           <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="menu.php"
                            onclick="loadSection('menu'); return false;"
                            class="nav-link block py-2 px-3 text-gray-700 hover:text-blue-600 md:p-0 relative group">
                            <span>Menu</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            onclick="loadSection('about'); return false;"
                            class="nav-link block py-2 px-3 text-gray-700 hover:text-blue-600 md:p-0 relative group">
                            <span>Tentang Kami</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            onclick="loadSection('section'); return false;"
                            class="nav-link block py-2 px-3 text-gray-700 hover:text-blue-600 md:p-0 relative group">
                            <span>Kontak</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mobile menu toggle
        const menuButton = document.querySelector("[data-collapse-toggle='mega-menu']");
        const megaMenu = document.getElementById("mega-menu");
        
        if (menuButton && megaMenu) {
            menuButton.addEventListener("click", function() {
                megaMenu.classList.toggle("hidden");
                menuButton.querySelector('svg').classList.toggle('rotate-90');
            });
        }
        
        // Scroll animation for navbar
        let lastScroll = 0;
        const header = document.querySelector('header');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                header.classList.remove('shadow-md');
                header.classList.remove('-translate-y-full');
                return;
            }
            
            if (currentScroll > lastScroll && !header.classList.contains('-translate-y-full')) {
                // Scroll down
                header.classList.add('-translate-y-full');
            } else if (currentScroll < lastScroll && header.classList.contains('-translate-y-full')) {
                // Scroll up
                header.classList.remove('-translate-y-full');
                header.classList.add('shadow-md');
            }
            
            lastScroll = currentScroll;
        });
        
        // Animate nav links on page load
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach((link, index) => {
            link.style.opacity = '0';
            link.style.transform = 'translateY(-10px)';
            link.style.transition = `opacity 0.3s ease ${index * 0.1}s, transform 0.3s ease ${index * 0.1}s`;
            
            setTimeout(() => {
                link.style.opacity = '1';
                link.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>

<style>
    /* Custom animation for login button */
    .nav-link {
        transition: color 0.3s ease;
    }
    
    /* Ensure the text stays above the pseudo-element */
    .nav-link span:first-child {
        position: relative;
        z-index: 1;
    }
</style>