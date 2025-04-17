

    <!-- CTA Section -->
    <section class="relative bg-gradient-to-br from-blue-600 to-blue-800 text-white py-20 overflow-hidden">
        <!-- Background elements with animations -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Animated floating circles -->
            <div class="absolute top-1/4 left-1/4 w-16 h-16 rounded-full bg-white/10 animate-float"></div>
            <div class="absolute bottom-1/3 right-1/4 w-20 h-20 rounded-full bg-white/5 animate-float-delay"></div>
            
            <!-- Noodle pattern background -->
            <div class="absolute inset-0 bg-[url('https://img.freepik.com/free-vector/noodle-background_23-2147746325.jpg')] bg-cover bg-center opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 to-blue-800/90"></div>
        </div>
        
        <div class="container mx-auto text-center px-6 relative z-10">
            <!-- Heading with animation -->
            <h2 class="text-4xl md:text-5xl font-bold mb-6 opacity-0 animate-fade-in-up animate-on-scroll">
                Siap Menikmati Kelezatan Mie Kami?
            </h2>
            
            <!-- Paragraph with delayed animation -->
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto opacity-0 animate-fade-in-up animate-delay-150 animate-on-scroll">
                Pesan sekarang dan nikmati pengalaman kuliner terbaik bersama Mieme! Dengan racikan bumbu tradisional dan bahan berkualitas premium.
            </p>
            
            <!-- Buttons with animations -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 opacity-0 animate-fade-in-up animate-delay-300 animate-on-scroll">
                <a href="login.php" 
                   class="bg-white text-blue-900 font-semibold px-8 py-4 rounded-lg shadow-lg hover:bg-gray-100 transition duration-300 hover:scale-105 hover:shadow-xl">
                    Pesan Sekarang
                </a>
                <a href="contact.php" 
                   class="border-2 border-white px-8 py-3.5 rounded-lg shadow-lg hover:bg-white hover:text-blue-600 transition duration-300 hover:scale-105 hover:shadow-xl">
                    Kontak Kami
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="p-8 md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Kunjungi Kami</h2>
                    <p class="text-gray-600 mb-6">
                        Jl. Rungkut Madya, Gn. Anyar, Kec. Gn. Anyar<br> 
                        Surabaya, Jawa Timur, indonesia 60294
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-700">Lokasi strategis di pusat kota</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">Buka setiap hari 24 Jam!</span>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 h-96">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.1795615347028!2d112.78574441048191!3d-7.333721192644144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fab87edcad15%3A0xb26589947991eea1!2sUniversitas%20Pembangunan%20Nasional%20%22Veteran%22%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1743772941490!5m2!1sid!2sid" 
                    width="600" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced scroll animation trigger
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0');
                        
                        // Add a small delay between animations for better visual effect
                        const delay = entry.target.classList.contains('animate-delay-100') ? 100 :
                                      entry.target.classList.contains('animate-delay-200') ? 200 :
                                      entry.target.classList.contains('animate-delay-300') ? 300 : 0;
                        
                        setTimeout(() => {
                            entry.target.style.animationPlayState = 'running';
                        }, delay);
                    }
                });
            }, { 
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px' // Trigger animation 100px before element comes into view
            });
            
            // Initialize all animations as paused
            animatedElements.forEach(element => {
                element.style.animationPlayState = 'paused';
                observer.observe(element);
            });
        });
    </script>

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