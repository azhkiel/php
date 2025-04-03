<?php
include "../service/database.php";
session_start();
$login_message = "";

// Memeriksa apakah pengguna sudah login. Jika iya, langsung dialihkan ke dashboard.php
if (isset($_SESSION["is_login"])) {
    // Redirect berdasarkan role jika sudah login
    switch ($_SESSION["role"]) {
        case "owner":
            header("Location: ../main/owner/dashOwner.php");
            break;
        case "admin":
            header("Location: ../main/admin/dashAdmin.php");
            break;
        case "customer":
            header("Location: ../main/customer/dashCustomer.php");
            break;
        case "staff":
            header("Location: ../main/staff/dashStaff.php");
            break;
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in-right': 'fadeInRight 1s ease-out forwards',
                        'fade-in-left': 'fadeInLeft 1s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out infinite 2s',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'fade-in-up-delay': 'fadeInUp 0.8s ease-out 0.3s forwards',
                        'scale-in': 'scaleIn 0.6s ease-out forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        fadeInRight: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateX(40px)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'translateX(0)'
                            },
                        },
                        fadeInLeft: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateX(-40px)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'translateX(0)'
                            },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeInUp: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        scaleIn: {
                            '0%': { 
                                opacity: '0',
                                transform: 'scale(0.95)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <title>Landing - Page</title>
</head>
<body>
    <?php include "../layout/header.php" ?>
    <main class="pt-[80px]">
        <div id="content">
            <!-- Hero Section -->
    <section class="relative w-full h-screen bg-cover bg-center flex items-center px-12 overflow-hidden" style="background-image: url('../assets/bg.png');">
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Floating background elements -->
        <div class="absolute top-20 left-1/4 w-16 h-16 rounded-full bg-white/5 animate-float"></div>
        <div class="absolute bottom-1/3 right-1/4 w-20 h-20 rounded-full bg-white/5 animate-float animate-delay-1000"></div>
        
        <div class="relative flex w-full max-w-screen-xl mx-auto flex-col md:flex-row">
            <!-- Bagian Kiri: Teks -->
            <div class="w-full md:w-1/2 text-white opacity-0 animate-fade-in-right">
                <div class="flex items-center space-x-4 mb-6">
                    <img src="../assets/img/mieme/Mieme.png" alt="judul" class="h-20 w-auto transform transition-all duration-500 hover:scale-105">
                </div>
                <p class="text-lg mb-6 leading-relaxed">
                    MieMe, dikelola oleh PT Keluarga Cemara, adalah restoran mie pedas No. 1 di Indonesia yang menghadirkan cita rasa khas dan pengalaman kuliner yang seru. 
                    Sebagai brand lokal, kami bangga menyajikan mie berkualitas dengan berbagai level kepedasan yang bisa disesuaikan selera—nikmat, pedas, dan bikin nagih!
                </p>
                <p class="text-lg mb-8 leading-relaxed">
                    Kami selalu berkomitmen untuk memberikan pengalaman kuliner terbaik bagi pelanggan dengan menggunakan bahan-bahan pilihan dan resep autentik.
                    Dengan suasana yang nyaman dan harga yang terjangkau, MieMe siap menjadi pilihan favorit pecinta mie di seluruh Indonesia!
                </p>
                <a href="#" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg inline-block transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Lihat Menu
                </a>
            </div>

            <!-- Bagian Kanan: Gambar Mie -->
            <div class="w-full md:w-1/2 flex justify-center mt-12 md:mt-0 opacity-0 animate-fade-in-left animate-delay-300">
                <img src="../assets/img/mieme/mieTP.png" alt="Mie Pedas" 
                    class="w-full max-w-[500px] rounded-lg shadow-2xl transform transition-all duration-500 hover:scale-105 animate-scale-in">
            </div>
        </div>
    </section>

    <script>
        // Simple animation trigger on load
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('[class*="animate-"]');
            
            animatedElements.forEach(element => {
                // Remove opacity-0 after a short delay to allow for animation
                setTimeout(() => {
                    element.classList.remove('opacity-0');
                }, 100);
            });
        });
    </script>
        <section id="promo">
            <?php include '../layout/menuData.php'; ?>
        </section>
        <section id="about">
            <?php include '../layout/about.php'; ?>
        </section>
        <?php include "../layout/section.php" ?>
    </div>
</main>
    <?php include "../layout/footer.php" ?>
</body>
<script>
    function loadSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            const yOffset = -80; // Sesuaikan offset (misal: tinggi header)
            const y = section.getBoundingClientRect().top + window.scrollY + yOffset;
            window.scrollTo({ top: y, behavior: "smooth" });
        }
    }
</script>
</html>
