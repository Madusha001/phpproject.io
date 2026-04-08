<?php
session_start();
include 'db_connection.php';
$student_id = $_SESSION['user_id'] ?? 0;

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Internships - InternSystem</title>
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

                <a href="internships.php" class="flex items-center gap-3 px-5 py-3.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02]">
                    <i class="ph ph-briefcase text-xl"></i> 
                    <span>Browse Internships</span>
                </a>

                <a href="my_applications.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium group">
                    <i class="ph ph-paper-plane-tilt text-xl group-hover:scale-110 transition-transform"></i> 
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
                <h2 class="text-2xl font-bold text-slate-800">Available Opportunities</h2>
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
                <div class="max-w-7xl mx-auto">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        
                        <?php
                        // Fetch active internships
                        $sql = "SELECT i.*, u.name as company_name FROM internships i JOIN users u ON i.company_id = u.id WHERE i.status = 'active' ORDER BY i.posted_date DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Create a random color/initial for the company logo placeholder
                                $initial = strtoupper(substr($row['company_name'], 0, 1));
                                
                                // Format posted date
                                $postedDate = date("M d, Y", strtotime($row['posted_date']));
                                
                                echo "
                                <div class='bg-white p-7 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-200 transition duration-300 flex flex-col h-full group'>
                                    <div class='flex items-start justify-between mb-5'>
                                        <div class='w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold text-2xl shadow-inner group-hover:bg-blue-50 group-hover:text-blue-600 transition'>
                                            $initial
                                        </div>
                                        <span class='text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100'>
                                            $postedDate
                                        </span>
                                    </div>
                                    
                                    <h3 class='font-bold text-xl text-slate-800 mb-1 leading-tight group-hover:text-blue-700 transition'>{$row['title']}</h3>
                                    <p class='text-sm text-slate-500 font-medium mb-5'>{$row['company_name']}</p>
                                    
                                    <div class='space-y-3 mb-6'>
                                        <div class='flex items-center gap-3 text-sm text-slate-600'>
                                            <div class='w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400'>
                                                <i class='ph-fill ph-map-pin'></i>
                                            </div>
                                            <span>{$row['type']}</span>
                                        </div>
                                        <div class='flex items-center gap-3 text-sm text-slate-600'>
                                            <div class='w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400'>
                                                <i class='ph-fill ph-clock'></i>
                                            </div>
                                            <span>{$row['duration']}</span>
                                        </div>
                                    </div>

                                    <div class='mt-auto pt-6 border-t border-slate-100'>
                                        <a href='apply_internship.php?id={$row['id']}' class='w-full py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition duration-300 flex justify-center items-center gap-2 group-hover:shadow-md'>
                                            Apply Now <i class='ph-bold ph-arrow-right'></i>
                                        </a>
                                    </div>
                                </div>";
                            }
                        } else {
                            echo "
                            <div class='col-span-full flex flex-col items-center justify-center py-20 text-center'>
                                <div class='w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4'>
                                    <i class='ph-fill ph-magnifying-glass text-4xl text-slate-400'></i>
                                </div>
                                <h3 class='text-xl font-bold text-slate-700'>No Internships Found</h3>
                                <p class='text-slate-500 mt-2'>There are currently no active listings available.</p>
                            </div>";
                        }
                        ?>

                    </div>
                    
                    <p class="text-center text-slate-400 text-sm mt-12 pb-8">&copy; 2026 InternSystem - University of Vavuniya</p>

                </div>
            </div>
        </main>
    </div>
</body>
</html>