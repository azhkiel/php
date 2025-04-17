<?php
include '../service/database.php';

$categories = ['Makanan', 'Minuman', 'Dessert'];
?>
<!-- Hero Header with Animation -->
<div class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20 mb-12 overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://img.freepik.com/free-vector/noodle-background_23-2147746325.jpg')] opacity-10 bg-cover"></div>
    <div class="container mx-auto text-center px-6 relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 opacity-0 animate-fade-in-up animate-on-scroll">
            Menu Menarik Kami
        </h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto opacity-0 animate-fade-in-up animate-delay-100 animate-on-scroll">
            Nikmati pilihan menu lezat kami yang dibuat dengan bahan-bahan premium
        </p>
    </div>
</div>

<div class="container mx-auto px-4 md:px-6">
    <?php foreach ($categories as $index => $category): ?>
        <?php
        $query = $db->prepare("SELECT * FROM menu WHERE kategori = ? ORDER BY nama_menu ASC");
        $query->bind_param("s", $category);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0): ?>
            <!-- Category Section with Staggered Animation -->
            <section class="mb-16 opacity-0 animate-fade-in-left animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms">
                <!-- Animated Category Header -->
                <div class="flex items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 relative inline-block">
                        <span class="relative z-10 px-4 bg-gray-50"><?= htmlspecialchars($category) ?> Pilihan</span>
                        <span class="absolute bottom-0 left-0 w-full h-2 bg-yellow-400 z-0"></span>
                    </h2>
                </div>

                <!-- Menu Grid with Staggered Animations -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($menu = $result->fetch_assoc()): ?>
                        <?php $imagePath = "../assets/img/menu/" . htmlspecialchars($menu["gambar"]); ?>

                        <!-- Animated Menu Card -->
                        <article class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col transform hover:-translate-y-2 opacity-0 animate-scale-in animate-on-scroll">
                            <!-- Image with Overlay -->
                            <div class="relative h-64 overflow-hidden">
                                <img src="<?= $imagePath ?>"
                                    alt="<?= htmlspecialchars($menu["nama_menu"]) ?>"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                <!-- Price Tag -->
                                <span class="absolute top-4 right-4 bg-white text-blue-600 font-bold py-2 px-4 rounded-full shadow-md">
                                    Rp <?= number_format($menu["harga"], 0, ',', '.') ?>
                                </span>
                            </div>

                            <!-- Menu Details -->
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold mb-2 text-gray-800"><?= htmlspecialchars($menu["nama_menu"]) ?></h3>

                                <p class="text-gray-600 mb-4 flex-grow">
                                    <?= htmlspecialchars($menu["deskripsi"]) ?>
                                </p>

                                <div class="flex justify-between items-center mt-auto">
                                    <!-- Rating Stars -->
                                    <div class="flex text-yellow-400">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- Order Button -->
                                    <!-- Change the button to redirect to login.php when clicked -->
                                    <a href="login.php" class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-medium py-2 px-6 rounded-full transition-all duration-300 transform hover:scale-105 inline-block text-center">
                                        Pesan
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-8 right-8 z-50 opacity-0 animate-fade-in-up animate-on-scroll" style="animation-delay: 300ms">
    <a href="login.php" class="bg-red-600 hover:bg-red-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center transition-all duration-300 transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    </a>
</div>

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