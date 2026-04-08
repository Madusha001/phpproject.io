<?php
session_start();
include 'db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Error handling helper (Optional: helps prevent crashes if DB fails)
function safe_get_count($conn, $sql) {
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        return $row ? $row['count'] : 0;
    }
    return 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white border-r border-slate-200 shadow-sm hidden md:flex flex-col z-10 relative">
            <div class="p-8">
                <a href="index.php" class="block group">
                    <div class="flex items-center gap-3">
                        <img src="logo.png" alt="InternSystem Logo" class="h-10 w-auto object-contain">
                        <h1 class="text-2xl font-bold text-blue-700 tracking-tight">InternSystem</h1>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 pl-1 font-medium tracking-wide uppercase">University of Vavuniya</p>
                </a>
            </div>
            
            <nav class="flex-1 px-4 space-y-2 mt-2">
                <a href="student_dashboard.php" class="flex items-center gap-3 px-5 py-3.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02]">
                    <i class="ph ph-squares-four text-xl"></i> Dashboard
                </a>
                
                <a href="student_profile.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-user text-xl group-hover:scale-110 transition-transform"></i> My Profile
                </a>
                <a href="internships.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-briefcase text-xl group-hover:scale-110 transition-transform"></i> Browse Internships
                </a>
                <a href="my_applications.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-paper-plane-tilt text-xl group-hover:scale-110 transition-transform"></i> My Applications
                </a>
            </nav>

            <div class="p-6 border-t border-slate-100">
                <a href="logout.php" class="flex items-center gap-3 px-5 py-3 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition font-medium">
                    <i class="ph ph-sign-out text-xl"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto p-8 lg:p-10">
                
                <header class="flex justify-between items-center mb-10">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Welcome Back, <span class="text-blue-600"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Student'); ?></span>! 👋</h2>
                        <p class="text-slate-500 mt-1">Here's what's happening with your internship journey.</p>
                    </div>
                    <div class="flex items-center gap-5">
                        <button class="relative p-2 text-slate-400 hover:text-blue-600 transition">
                            <i class="ph ph-bell text-2xl"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                        </button>
                        <div class="flex items-center gap-3 pl-5 border-l border-slate-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></p>
                                <p class="text-xs text-slate-500">Student</p>
                            </div>
                            <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md shadow-blue-200">
                                <?php echo strtoupper(substr($_SESSION['name'] ?? 'S', 0, 1)); ?>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    
                    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between items-center mb-4">
                            <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                                <i class="ph-fill ph-paper-plane-tilt text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Applications</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">
                                <?php 
                                echo safe_get_count($conn, "SELECT COUNT(*) as count FROM applications WHERE student_id = $student_id");
                                ?>
                            </h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between items-center mb-4">
                            <div class="bg-amber-50 p-3 rounded-xl text-amber-600">
                                <i class="ph-fill ph-clock-countdown text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Pending Reviews</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">
                                <?php 
                                echo safe_get_count($conn, "SELECT COUNT(*) as count FROM applications WHERE student_id = $student_id AND status = 'pending'");
                                ?>
                            </h3>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600 p-6 rounded-2xl shadow-lg shadow-blue-200 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-2 opacity-90">
                                <i class="ph ph-sparkle"></i> <span class="text-sm font-medium">Pro Tip</span>
                            </div>
                            <h3 class="font-bold text-lg leading-tight mb-2">Update your CV often!</h3>
                            <p class="text-blue-100 text-xs">Keeping your skills updated increases visibility to companies.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-slate-800">Recent Applications</h3>
                            <a href="my_applications.php" class="text-sm text-blue-600 font-medium hover:underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-500 border-b border-slate-100 uppercase text-xs font-semibold tracking-wider">
                                    <tr>
                                        <th class="py-4 pl-6">Company</th>
                                        <th class="py-4">Role</th>
                                        <th class="py-4">Applied Date</th>
                                        <th class="py-4 pr-6">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php
                                    $sql = "SELECT a.status, a.applied_date, i.title, u.name as company_name 
                                            FROM applications a 
                                            JOIN internships i ON a.internship_id = i.id 
                                            JOIN users u ON i.company_id = u.id 
                                            WHERE a.student_id = $student_id
                                            ORDER BY a.applied_date DESC LIMIT 5";
                                    
                                    // Robust query execution
                                    $result = $conn->query($sql);
                                    
                                    if ($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $statusStyle = '';
                                            $icon = '';
                                            
                                            switch($row['status']) {
                                                case 'accepted':
                                                    $statusStyle = 'bg-green-100 text-green-700 border border-green-200';
                                                    $icon = '<i class="ph-fill ph-check-circle mr-1"></i>';
                                                    break;
                                                case 'rejected':
                                                    $statusStyle = 'bg-red-50 text-red-600 border border-red-100';
                                                    $icon = '<i class="ph-fill ph-x-circle mr-1"></i>';
                                                    break;
                                                default:
                                                    $statusStyle = 'bg-amber-50 text-amber-600 border border-amber-100';
                                                    $icon = '<i class="ph-fill ph-clock mr-1"></i>';
                                            }
                                            
                                            // Safer Date Formatting
                                            $formattedDate = 'N/A';
                                            if (!empty($row['applied_date'])) {
                                                try {
                                                    $dateObj = new DateTime($row['applied_date']);
                                                    $formattedDate = $dateObj->format('M d, Y');
                                                } catch (Exception $e) {
                                                    $formattedDate = $row['applied_date'];
                                                }
                                            }
                                            
                                            echo "<tr class='hover:bg-slate-50 transition duration-150'>
                                                    <td class='py-4 pl-6 font-semibold text-slate-700'>".htmlspecialchars($row['company_name'])."</td>
                                                    <td class='py-4 text-slate-600'>".htmlspecialchars($row['title'])."</td>
                                                    <td class='py-4 text-slate-500 font-medium'>{$formattedDate}</td>
                                                    <td class='py-4 pr-6'>
                                                        <span class='inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold $statusStyle'>
                                                            $icon " . ucfirst($row['status']) . "
                                                        </span>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='py-8 text-center text-slate-400 italic'>No applications found yet. Start applying!</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                            
                            <div class="relative z-10">
                                <h3 class="font-bold text-lg text-slate-800 mb-2">Complete Your Profile</h3>
                                <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                                    A complete profile increases your chances of getting hired by <span class="font-bold text-blue-600">40%</span>. Upload your CV now.
                                </p>
                                
                                <a href="student_profile.php" class="flex justify-center items-center w-full py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all transform active:scale-95">
                                    Update Profile <i class="ph-bold ph-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                            <h4 class="font-bold text-slate-700 mb-3 text-sm">Need Help?</h4>
                            <ul class="space-y-3 text-sm text-slate-600">
                                <li class="flex items-center gap-2 hover:text-blue-600 cursor-pointer transition">
                                    <i class="ph ph-question text-lg"></i> How to apply?
                                </li>
                                <li class="flex items-center gap-2 hover:text-blue-600 cursor-pointer transition">
                                    <i class="ph ph-file-text text-lg"></i> CV Guidelines
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>