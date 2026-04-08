<?php
session_start();
include 'db_connection.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white border-r border-slate-200 shadow-sm hidden md:flex flex-col z-20">
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
                <a href="student_dashboard.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-squares-four text-xl group-hover:scale-110 transition-transform"></i> 
                    <span>Dashboard</span>
                </a>
                
                <a href="student_profile.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-user text-xl group-hover:scale-110 transition-transform"></i> 
                    <span>My Profile</span>
                </a>

                <a href="internships.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-briefcase text-xl group-hover:scale-110 transition-transform"></i> 
                    <span>Browse Internships</span>
                </a>

                <a href="my_applications.php" class="flex items-center gap-3 px-5 py-3.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02]">
                    <i class="ph ph-paper-plane-tilt text-xl"></i> 
                    <span>My Applications</span>
                </a>
                
                </nav>

            <div class="p-6 border-t border-slate-100">
                <a href="logout.php" class="flex items-center gap-3 px-5 py-3 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition font-medium">
                    <i class="ph ph-sign-out text-xl"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <header class="bg-white border-b border-slate-200 shadow-sm h-20 flex items-center justify-between px-10 z-10">
                <h2 class="text-2xl font-bold text-slate-800">Application History</h2>
                <div class="flex items-center gap-5">
                    <button class="relative p-2 text-slate-400 hover:text-blue-600 transition">
                        <i class="ph ph-bell text-2xl"></i>
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

            <div class="flex-1 overflow-y-auto p-8 lg:p-10">
                <div class="max-w-6xl mx-auto">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800">Submitted Applications</h3>
                                <p class="text-sm text-slate-500">Track the status of your internship requests</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold tracking-wider">
                                    <tr>
                                        <th class="px-8 py-5">Company</th>
                                        <th class="px-8 py-5">Position</th>
                                        <th class="px-8 py-5">Applied Date</th>
                                        <th class="px-8 py-5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php
                                    // Using Prepared Statement for security
                                    $stmt = $conn->prepare("SELECT a.*, i.title, u.name as company_name 
                                                            FROM applications a 
                                                            JOIN internships i ON a.internship_id = i.id 
                                                            JOIN users u ON i.company_id = u.id 
                                                            WHERE a.student_id = ? 
                                                            ORDER BY a.applied_date DESC");
                                    $stmt->bind_param("i", $student_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $status = strtolower($row['status']);
                                            
                                            // Define badge styles based on status
                                            $badgeClass = 'bg-slate-100 text-slate-600';
                                            $icon = 'ph-minus';

                                            if ($status == 'accepted') {
                                                $badgeClass = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                                                $icon = 'ph-check-circle';
                                            } elseif ($status == 'rejected') {
                                                $badgeClass = 'bg-red-50 text-red-600 border border-red-100';
                                                $icon = 'ph-x-circle';
                                            } elseif ($status == 'pending') {
                                                $badgeClass = 'bg-amber-50 text-amber-600 border border-amber-100';
                                                $icon = 'ph-clock';
                                            }

                                            $date = date("M d, Y", strtotime($row['applied_date']));
                                            $companyInitial = strtoupper(substr($row['company_name'], 0, 1));
                                            
                                            echo "<tr class='hover:bg-slate-50 transition duration-150 group'>
                                                <td class='px-8 py-5'>
                                                    <div class='flex items-center gap-4'>
                                                        <div class='w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg'>
                                                            $companyInitial
                                                        </div>
                                                        <div class='font-bold text-slate-800'>".htmlspecialchars($row['company_name'])."</div>
                                                    </div>
                                                </td>
                                                <td class='px-8 py-5 font-medium text-slate-600'>".htmlspecialchars($row['title'])."</td>
                                                <td class='px-8 py-5 text-slate-500 text-sm'>
                                                    <div class='flex items-center gap-2'>
                                                        <i class='ph ph-calendar-blank'></i> $date
                                                    </div>
                                                </td>
                                                <td class='px-8 py-5 text-center'>
                                                    <span class='inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide $badgeClass'>
                                                        <i class='ph-fill $icon text-sm'></i>
                                                        ".ucfirst($row['status'])."
                                                    </span>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='px-6 py-20 text-center text-slate-400 italic flex flex-col items-center justify-center'>
                                            <div class='w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3'>
                                                <i class='ph ph-files text-3xl opacity-50'></i>
                                            </div>
                                            <p class='text-lg font-medium text-slate-600 italic'>No applications yet.</p>
                                            <p class='text-sm mt-1'>Start by browsing available internships!</p>
                                            <a href='internships.php' class='mt-4 text-blue-600 font-bold hover:underline'>Browse Internships &rarr;</a>
                                        </td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <p class="text-center text-slate-400 text-sm mt-12 pb-4">&copy; 2026 InternSystem - University of Vavuniya</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>