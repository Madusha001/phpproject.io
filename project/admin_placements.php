<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// --- Handle Placement Actions ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $app_id = intval($_GET['id']);
    $action = $_GET['action']; // 'approve' or 'reject'
    
    // Determine new status
    $new_status = ($action == 'approve') ? 'accepted' : 'rejected';
    
    // Prepare statement for security
    $stmt = $conn->prepare("UPDATE applications SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $app_id);
    
    if ($stmt->execute()) {
        $msg = "Application has been " . $new_status . ".";
        $msg_type = ($new_status == 'accepted') ? 'green' : 'red';
    } else {
        $msg = "Error updating record.";
        $msg_type = 'red';
    }
    $stmt->close();
    
    // Redirect to clear query params (Optional, improves UX)
    // header("Location: admin_placements.php"); 
    // exit(); 
}

// --- Fetch Pending Count ---
$pending_count_result = $conn->query("SELECT COUNT(*) FROM applications WHERE status='pending'");
$pending_count = $pending_count_result ? $pending_count_result->fetch_row()[0] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Approvals - Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
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
                <a href="admin_placements.php" class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-stamp text-xl"></i> 
                    Placements
                    <?php if($pending_count > 0): ?>
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $pending_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin_reports.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-file-text text-xl"></i> 
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
                <h2 class="text-xl font-bold text-gray-800">Placement Management</h2>
                
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

                <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Review Applications</h3>
                        <p class="text-gray-500 mt-1">Manage student internship placements and university approvals.</p>
                    </div>
                    
                    <div class="flex items-center gap-2 px-4 py-2 bg-white border border-purple-100 rounded-lg shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                            <i class="ph ph-clock-countdown"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 font-bold uppercase">Pending</span>
                            <span class="text-lg font-bold text-gray-900 leading-none block"><?php echo $pending_count; ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Pending Requests</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-gray-500 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Student Details</th>
                                    <th class="px-6 py-4 font-semibold">Company</th>
                                    <th class="px-6 py-4 font-semibold">Position Info</th>
                                    <th class="px-6 py-4 font-semibold">Status</th>
                                    <th class="px-6 py-4 font-semibold text-center">Admin Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                // SQL to fetch all necessary details
                                $sql = "SELECT a.id, a.status, 
                                               u_s.name as student_name, s.reg_no, 
                                               u_c.name as company_name, i.title, i.duration 
                                        FROM applications a
                                        JOIN users u_s ON a.student_id = u_s.id
                                        JOIN students s ON u_s.id = s.user_id
                                        JOIN internships i ON a.internship_id = i.id
                                        JOIN users u_c ON i.company_id = u_c.id
                                        WHERE a.status = 'pending' 
                                        ORDER BY a.applied_date DESC";
                                
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='px-6 py-4'>
                                                <div class='flex items-center gap-3'>
                                                    <div class='w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold'>
                                                        <i class='ph ph-student'></i>
                                                    </div>
                                                    <div>
                                                        <div class='font-bold text-gray-900'>".htmlspecialchars($row['student_name'])."</div>
                                                        <div class='text-xs text-gray-500'>".htmlspecialchars($row['reg_no'])."</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='px-6 py-4'>
                                                <div class='font-medium text-gray-900'>".htmlspecialchars($row['company_name'])."</div>
                                            </td>
                                            <td class='px-6 py-4'>
                                                <div class='text-gray-900 font-medium'>".htmlspecialchars($row['title'])."</div>
                                                <div class='text-xs text-gray-500'>".htmlspecialchars($row['duration'])."</div>
                                            </td>
                                            <td class='px-6 py-4'>
                                                <span class='inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700'>
                                                    <span class='w-1.5 h-1.5 rounded-full bg-orange-500'></span>
                                                    Pending
                                                </span>
                                            </td>
                                            <td class='px-6 py-4 text-center'>
                                                <div class='flex items-center justify-center gap-2'>
                                                    <a href='admin_placements.php?action=approve&id={$row['id']}' 
                                                       class='p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition' 
                                                       title='Approve Placement'>
                                                        <i class='ph ph-check text-lg'></i>
                                                    </a>
                                                    <a href='admin_placements.php?action=reject&id={$row['id']}' 
                                                       class='p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition' 
                                                       title='Reject Placement' 
                                                       onclick='return confirm(\"Are you sure you want to reject this placement?\")'>
                                                        <i class='ph ph-trash text-lg'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='px-6 py-12 text-center text-gray-500'>
                                        <div class='flex flex-col items-center justify-center'>
                                            <i class='ph ph-check-circle text-4xl text-gray-300 mb-2'></i>
                                            <p>All clear! No pending placements.</p>
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