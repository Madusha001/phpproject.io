<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// --- Report Calculations ---

// 1. Total Students
$total_students_res = $conn->query("SELECT COUNT(*) FROM students");
$total_students = $total_students_res ? $total_students_res->fetch_row()[0] : 0;

// 2. Placed Students (Accepted status)
$placed_students_res = $conn->query("SELECT COUNT(DISTINCT student_id) FROM applications WHERE status='accepted'");
$placed_students = $placed_students_res ? $placed_students_res->fetch_row()[0] : 0;

// 3. Active Internships (Offers that are currently active/open)
$active_internships_res = $conn->query("SELECT COUNT(*) FROM internships WHERE status='active'");
$active_internships = $active_internships_res ? $active_internships_res->fetch_row()[0] : 0;

// 4. Placement Rate
$placement_rate = ($total_students > 0) ? round(($placed_students / $total_students) * 100, 1) : 0;

// 5. Total Companies
$total_companies_res = $conn->query("SELECT COUNT(*) FROM users WHERE role='company'");
$total_companies = $total_companies_res ? $total_companies_res->fetch_row()[0] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        @media print {
            aside, header, .no-print { display: none !important; }
            main { padding: 0 !important; overflow: visible !important; }
            .shadow-sm, .shadow-lg { box-shadow: none !important; border: 1px solid #ddd; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-100 flex flex-col items-center">
                 <img src="logo.png" alt="InternSystem Logo" class="h-12 w-auto object-contain">
               <span class="text-xl font-bold text-gray-900 tracking-tight">InternSystem</span>
                <span class="text-xs font-bold text-purple-500 uppercase tracking-wider">Admin Portal</span>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-6">
                <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-squares-four text-xl"></i> 
                    Overview
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-student text-xl"></i> 
                    Manage Students
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-buildings text-xl"></i> 
                    Manage Companies
                </a>
                <a href="admin_placements.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-stamp text-xl"></i> 
                    Placements
                </a>
                <a href="admin_reports.php" class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-chart-bar text-xl"></i> 
                    Reports
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg transition font-medium">
                    <i class="ph ph-sign-out text-xl"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-xl font-bold text-gray-800">Reports & Analytics</h2>
                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($admin_name); ?></p>
                        <p class="text-xs text-purple-600">Administrator</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 font-bold border border-purple-200">
                        UA
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">

                <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Placement Overview</h3>
                        <p class="text-gray-500 mt-1">Key metrics and performance indicators for the current academic year.</p>
                    </div>
                    <button onclick="window.print()" class="no-print flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm font-medium">
                        <i class="ph ph-printer text-lg"></i> Print Report
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-blue-300 transition-colors">
                        <div class="relative z-10">
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Students</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $total_students; ?></h3>
                        </div>
                        <div class="absolute right-4 top-4 p-3 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="ph ph-users text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-green-300 transition-colors">
                        <div class="relative z-10">
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Placed Students</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $placed_students; ?></h3>
                            <p class="text-xs text-green-600 font-medium mt-1 flex items-center gap-1">
                                <i class="ph ph-trend-up"></i> Confirmed
                            </p>
                        </div>
                        <div class="absolute right-4 top-4 p-3 bg-green-50 text-green-600 rounded-lg">
                            <i class="ph ph-check-circle text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-purple-300 transition-colors">
                        <div class="relative z-10">
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Active Openings</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $active_internships; ?></h3>
                            <p class="text-xs text-purple-600 font-medium mt-1">Across <?php echo $total_companies; ?> Companies</p>
                        </div>
                        <div class="absolute right-4 top-4 p-3 bg-purple-50 text-purple-600 rounded-lg">
                            <i class="ph ph-briefcase text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-orange-300 transition-colors">
                        <div class="relative z-10">
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Success Rate</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $placement_rate; ?>%</h3>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3">
                                <div class="bg-orange-500 h-1.5 rounded-full" style="width: <?php echo $placement_rate; ?>%"></div>
                            </div>
                        </div>
                        <div class="absolute right-4 top-4 p-3 bg-orange-50 text-orange-600 rounded-lg">
                            <i class="ph ph-chart-pie-slice text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Placement Statistics by Degree Program</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-white text-gray-500 border-b border-gray-100 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Degree Program</th>
                                    <th class="px-6 py-4 text-center">Total Students</th>
                                    <th class="px-6 py-4 text-center">Placed</th>
                                    <th class="px-6 py-4 text-center">Rate</th>
                                    <th class="px-6 py-4 w-1/4">Progress</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php
                                // Helper function to calculate stats
                                function getDegreeStats($conn, $pattern) {
                                    // Use Prepared Statements logic or cleaner queries in production
                                    // For now, using direct queries as requested in pattern
                                    $sql_total = "SELECT COUNT(*) FROM students WHERE reg_no LIKE '%$pattern%'";
                                    $res_total = $conn->query($sql_total);
                                    $total = $res_total ? $res_total->fetch_row()[0] : 0;

                                    $sql_placed = "SELECT COUNT(DISTINCT s.user_id) FROM students s 
                                                   JOIN applications a ON s.user_id = a.student_id 
                                                   WHERE s.reg_no LIKE '%$pattern%' AND a.status='accepted'";
                                    $res_placed = $conn->query($sql_placed);
                                    $placed = $res_placed ? $res_placed->fetch_row()[0] : 0;

                                    $rate = ($total > 0) ? round(($placed / $total) * 100) : 0;
                                    return ['total' => $total, 'placed' => $placed, 'rate' => $rate];
                                }

                                // Degree Programs Configuration
                                $programs = [
                                    ['name' => 'BSc in ICT', 'code' => 'ICT', 'color' => 'blue'],
                                    ['name' => 'BSc in Applied Mathematics', 'code' => 'AMT', 'color' => 'purple'],
                                    ['name' => 'BSc in Environmental Science', 'code' => 'ENV', 'color' => 'green'],
                                    ['name' => 'BSc in Project Management', 'code' => 'PM', 'color' => 'orange']
                                ];

                                foreach ($programs as $prog) {
                                    $stats = getDegreeStats($conn, $prog['code']);
                                    $colorClass = "bg-{$prog['color']}-500";
                                    
                                    echo "<tr class='hover:bg-gray-50 transition'>
                                        <td class='px-6 py-4 font-medium text-gray-800'>{$prog['name']}</td>
                                        <td class='px-6 py-4 text-center text-gray-600'>{$stats['total']}</td>
                                        <td class='px-6 py-4 text-center font-bold text-gray-900'>{$stats['placed']}</td>
                                        <td class='px-6 py-4 text-center'>{$stats['rate']}%</td>
                                        <td class='px-6 py-4'>
                                            <div class='w-full bg-gray-100 rounded-full h-2'>
                                                <div class='$colorClass h-2 rounded-full transition-all duration-500' style='width: {$stats['rate']}%'></div>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.querySelector('.fallback-img').onerror = function() {
            this.style.display = 'none';
            const fallback = document.createElement('div');
            fallback.className = 'text-center mb-2';
            fallback.innerHTML = '<i class="ph ph-graduation-cap text-4xl text-purple-600"></i><h1 class="font-bold text-gray-800 mt-1">InternSystem</h1>';
            this.parentElement.insertBefore(fallback, this.nextSibling);
        };
    </script>
</body>
</html>