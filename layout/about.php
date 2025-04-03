<!-- About Us Section -->
<section class="relative py-20 bg-gradient-to-br from-blue-600 to-blue-800 text-white overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full mix-blend-overlay animate-float"></div>
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-white rounded-full mix-blend-overlay animate-float-delay"></div>
    </div>
    
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Content Column -->
        <div class="relative z-10">
            <!-- Section Title with Underline Animation -->
            <div class="mb-8 overflow-hidden">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 opacity-0 animate-fade-in-right animate-on-scroll">
                    Tentang <span class="text-yellow-300">Mieme</span>
                </h2>
                <div class="h-1 w-20 bg-yellow-300 opacity-0 animate-scale-in animate-on-scroll" style="animation-delay: 100ms"></div>
            </div>
            
            <!-- Description with Animation -->
            <p class="text-xl mb-8 leading-relaxed opacity-0 animate-fade-in-up animate-on-scroll" style="animation-delay: 200ms">
                Mieme adalah restoran mie premium yang didirikan pada tahun 2020 dengan visi menyajikan hidangan mie berkualitas tinggi menggunakan bahan-bahan pilihan dan resep rahasia keluarga.
            </p>

            <!-- Vision & Mission Grid with Staggered Animations -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <!-- Vision Card -->
                <div class="bg-white text-blue-600 p-6 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 opacity-0 animate-scale-in animate-on-scroll" style="animation-delay: 300ms">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold">Visi Kami</h4>
                    </div>
                    <p>Menjadi jaringan restoran mie terbaik dan terpercaya di seluruh Indonesia dengan cita rasa yang konsisten.</p>
                </div>
                
                <!-- Mission Card -->
                <div class="bg-white text-blue-600 p-6 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 opacity-0 animate-scale-in animate-on-scroll" style="animation-delay: 400ms">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold">Misi Kami</h4>
                    </div>
                    <p>Menyediakan hidangan mie berkualitas premium dengan harga terjangkau dan pelayanan terbaik bagi pelanggan.</p>
                </div>
            </div>

            <!-- Animated Button -->
            <a href="about.php" 
               class="inline-block bg-yellow-400 hover:bg-yellow-300 text-blue-800 font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105 opacity-0 animate-fade-in-up animate-on-scroll" 
               style="animation-delay: 500ms">
               <span class="flex items-center">
                   Pelajari Lebih Lanjut
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                       <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                   </svg>
               </span>
            </a>
        </div>

        <!-- Image Column -->
        <div class="relative z-10 opacity-0 animate-fade-in-left animate-on-scroll">
            <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                <img src="../assets/Mie.jpg" alt="Tentang Mieme" 
                     class="w-full h-auto object-cover transform transition-all duration-700 hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent"></div>
                <!-- Decorative Badge -->
                <div class="absolute bottom-6 left-6 bg-yellow-400 text-blue-800 font-bold py-2 px-4 rounded-full shadow-lg animate-bounce-slow">
                    Since 2020
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Scroll animation trigger
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('opacity-0');
                }
            });
        }, { 
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animatedElements.forEach(element => {
            observer.observe(element);
        });
    });
</script>