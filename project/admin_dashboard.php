<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// --- Statistics ---
$total_students = $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetch_row()[0];
$total_companies = $conn->query("SELECT COUNT(*) FROM users WHERE role='company'")->fetch_row()[0];
$active_internships = $conn->query("SELECT COUNT(*) FROM internships WHERE status='active'")->fetch_row()[0];
$pending_approvals = $conn->query("SELECT COUNT(*) FROM applications WHERE status='pending'")->fetch_row()[0];

// --- Handle Company Actions (Approve/Reject) ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    
    if ($_GET['action'] == 'reject') {
        // Prepare statement for security
        $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='company'");
        $stmt->bind_param("i", $target_id);
        
        if ($stmt->execute()) {
            $msg = "Company profile rejected and removed.";
            $msg_type = "red";
        }
        $stmt->close();
    } elseif ($_GET['action'] == 'approve') {
        // Here you would typically update a 'status' column. 
        // For now, we'll just show a success message as per your logic.
        $msg = "Company profile approved successfully.";
        $msg_type = "green";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-100 flex flex-col items-center">
                <img src="logo.png" alt="InternSystem Logo" class="h-12 w-auto object-contain fallback-img">
                <span class="text-xl font-bold text-gray-900 tracking-tight mt-2">InternSystem</span>
                <span class="text-xs font-bold text-purple-500 uppercase tracking-wider">Admin Portal</span>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-6">
                <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-squares-four text-xl"></i> Overview
                </a>
                <a href="manage_students.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-student text-xl"></i> Manage Students
                </a>
                <a href="manage_companies.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-buildings text-xl"></i> Manage Companies
                </a>
                <a href="admin_placements.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-stamp text-xl"></i> Placements
                </a>
                <a href="admin_reports.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-file-text text-xl"></i> Reports
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
                <h2 class="text-xl font-bold text-gray-800">System Overview</h2>
                
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

                <?php if (isset($msg)): ?>
                <div class="mb-6 p-4 rounded-lg bg-<?php echo $msg_type; ?>-50 text-<?php echo $msg_type; ?>-700 border border-<?php echo $msg_type; ?>-200 flex items-center gap-2">
                    <i class="ph ph-info text-lg"></i> <?php echo $msg; ?>
                </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-student"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Students</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $total_students; ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-buildings"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Companies</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $total_companies; ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-briefcase"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Active Internships</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $active_internships; ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-clock-countdown"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Pending Apps</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $pending_approvals; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-800">New Company Registrations</h3>
                        <a href="manage_companies.php" class="text-sm text-purple-600 font-semibold hover:underline">View All Companies</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-gray-500 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Company Name</th>
                                    <th class="px-6 py-4 font-semibold">Email Contact</th>
                                    <th class="px-6 py-4 font-semibold">Joined Date</th>
                                    <th class="px-6 py-4 font-semibold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                $companies = $conn->query("SELECT * FROM users WHERE role='company' ORDER BY created_at DESC LIMIT 5");
                                
                                if ($companies->num_rows > 0) {
                                    while($row = $companies->fetch_assoc()) {
                                        $joinedDate = date('M d, Y', strtotime($row['created_at']));
                                        echo "<tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='px-6 py-4'>
                                                <div class='flex items-center gap-3'>
                                                    <div class='w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs'>
                                                        ".substr($row['name'], 0, 1)."
                                                    </div>
                                                    <span class='font-medium text-gray-900'>".htmlspecialchars($row['name'])."</span>
                                                </div>
                                            </td>
                                            <td class='px-6 py-4 text-gray-500'>".htmlspecialchars($row['email'])."</td>
                                            <td class='px-6 py-4 text-gray-500'>{$joinedDate}</td>
                                            <td class='px-6 py-4 text-center'>
                                                <div class='flex items-center justify-center gap-2'>
                                                    <a href='admin_dashboard.php?action=approve&id={$row['id']}' 
                                                       class='flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg text-xs font-bold transition'>
                                                        <i class='ph ph-check'></i> Approve
                                                    </a>
                                                    <a href='admin_dashboard.php?action=reject&id={$row['id']}' 
                                                       onclick='return confirm(\"Are you sure you want to delete this company?\")'
                                                       class='flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-xs font-bold transition'>
                                                        <i class='ph ph-trash'></i> Reject
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='px-6 py-12 text-center text-gray-500'>
                                            <div class='flex flex-col items-center justify-center'>
                                                <i class='ph ph-buildings text-4xl text-gray-300 mb-2'></i>
                                                <p>No new companies found.</p>
                                            </div>
                                        </td></tr>";
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
        // Fallback image logic
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