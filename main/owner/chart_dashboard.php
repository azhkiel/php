<?php
include '../../service/database.php';
session_start();

// Check if user is owner/admin
if ($_SESSION['role'] !== 'owner' && $_SESSION['role'] !== 'admin') {
    header("Location: dashOwner.php");
    exit();
}

// Get most popular menu items
$popularMenu = $db->query("
    SELECT m.kode_menu, m.nama_menu, COUNT(od.id) as total_orders 
    FROM order_details od
    JOIN menu m ON od.kode_menu = m.kode_menu
    GROUP BY m.kode_menu
    ORDER BY total_orders DESC
    LIMIT 5
");

// Get daily revenue data (last 7 days)
$dailyRevenue = $db->query("
    SELECT 
        DATE(created_at) as order_date,
        SUM(total_price) as daily_total
    FROM `order`
    WHERE status = 'completed'
    AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY order_date ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <div class="flex">
        <?php include "sidebar.php"; ?>
        
        <!-- Main Content -->
        <div class="ml-64 flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Analytics Dashboard</h1>
                <p class="text-gray-600">Insight into your restaurant performance</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Revenue Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Revenue</p>
                            <?php
                            $totalRevenue = $db->query("SELECT SUM(total_price) as total FROM `order` WHERE status = 'completed'")->fetch_assoc();
                            ?>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp <?= number_format($totalRevenue['total'], 0, ',', '.') ?></h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Orders</p>
                            <?php
                            $totalOrders = $db->query("SELECT COUNT(*) as total FROM `order` WHERE status = 'completed'")->fetch_assoc();
                            ?>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($totalOrders['total'], 0) ?></h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Popular Item Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Most Popular Item</p>
                            <?php
                            $popularItem = $db->query("
                                SELECT m.nama_menu 
                                FROM order_details od
                                JOIN menu m ON od.kode_menu = m.kode_menu
                                GROUP BY m.kode_menu
                                ORDER BY COUNT(od.id) DESC
                                LIMIT 1
                            ")->fetch_assoc();
                            ?>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $popularItem['nama_menu'] ?? 'N/A' ?></h3>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Popular Menu Chart -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Top 5 Most Ordered Menu</h2>
                    <div class="h-80">
                        <canvas id="popularMenuChart"></canvas>
                    </div>
                </div>

                <!-- Daily Revenue Chart -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daily Revenue (Last 7 Days)</h2>
                    <div class="h-80">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Popular Menu Chart
        const popularMenuCtx = document.getElementById('popularMenuChart').getContext('2d');
        const popularMenuChart = new Chart(popularMenuCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php while($row = $popularMenu->fetch_assoc()): ?>
                        '<?= $row['nama_menu'] ?>',
                    <?php endwhile; ?>
                ],
                datasets: [{
                    label: 'Number of Orders',
                    data: [
                        <?php 
                        $popularMenu->data_seek(0); // Reset pointer
                        while($row = $popularMenu->fetch_assoc()): ?>
                            <?= $row['total_orders'] ?>,
                        <?php endwhile; ?>
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Orders: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });

        // Daily Revenue Chart
        const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
        const dailyRevenueChart = new Chart(dailyRevenueCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php while($row = $dailyRevenue->fetch_assoc()): ?>
                        '<?= date('M j', strtotime($row['order_date'])) ?>',
                    <?php endwhile; ?>
                ],
                datasets: [{
                    label: 'Daily Revenue (Rp)',
                    data: [
                        <?php 
                        $dailyRevenue->data_seek(0); // Reset pointer
                        while($row = $dailyRevenue->fetch_assoc()): ?>
                            <?= $row['daily_total'] ?>,
                        <?php endwhile; ?>
                    ],
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>