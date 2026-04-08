<?php
session_start();
include 'db_connection.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// Handle Actions (Approve/Reject/Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'delete' || $action == 'reject') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='company'");
        $stmt->bind_param("i", $target_id);
        if ($stmt->execute()) {
            $msg = "Company profile removed successfully.";
            $msg_type = "green";
        }
        $stmt->close();
    } elseif ($action == 'approve') {
        // Here assuming you might want to have a 'status' column later.
        // For now, simply showing success message as user is already in users table.
        // If you had a 'is_approved' column, update it here.
        $msg = "Company approved successfully.";
        $msg_type = "green";
    }
}

// Fetch Companies
$sql = "SELECT * FROM users WHERE role='company' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Companies - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-100 flex flex-col items-center">
                <img src="logo.png" alt="InternSystem" class="h-12 w-auto object-contain fallback-img">
                <span class="text-xl font-bold text-gray-900 tracking-tight mt-2">InternSystem</span>
                <span class="text-xs font-bold text-purple-500 uppercase tracking-wider">Admin Portal</span>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-6">
                <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-squares-four text-xl"></i> Overview
                </a>
                <a href="manage_students.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-student text-xl"></i> Manage Students
                </a>
                <a href="manage_companies.php" class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-700 rounded-lg font-semibold transition-colors">
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
                <h2 class="text-xl font-bold text-gray-800">Company Partners</h2>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($admin_name); ?></p>
                        <p class="text-xs text-purple-600">Administrator</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 font-bold border border-purple-200">UA</div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">
                
                <?php if (isset($msg)): ?>
                <div class="mb-6 p-4 rounded-lg bg-<?php echo $msg_type; ?>-50 text-<?php echo $msg_type; ?>-700 border border-<?php echo $msg_type; ?>-200 flex items-center gap-2">
                    <i class="ph ph-info text-lg"></i> <?php echo $msg; ?>
                </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search companies..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Company Name</th>
                                    <th class="px-6 py-4">Contact Email</th>
                                    <th class="px-6 py-4 text-center">Joined Date</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs uppercase">
                                                    <?php echo substr($row['name'], 0, 1); ?>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            <?php echo htmlspecialchars($row['email']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-500">
                                            <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="manage_companies.php?action=approve&id=<?php echo $row['id']; ?>" 
                                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Approve">
                                                    <i class="ph ph-check-circle text-lg"></i>
                                                </a>
                                                <a href="manage_companies.php?action=delete&id=<?php echo $row['id']; ?>" 
                                                   onclick="return confirm('Are you sure you want to delete this company? All their internships will be lost.');"
                                                   class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Remove">
                                                    <i class="ph ph-trash text-lg"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No companies found.</td></tr>
                                <?php endif; ?>
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
        };
    </script>
</body>
</html>