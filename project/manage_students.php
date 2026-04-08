<?php
session_start();
include 'db_connection.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// Handle Delete Action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='student'");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $msg = "Student record deleted successfully.";
        $msg_type = "green";
    } else {
        $msg = "Error deleting student.";
        $msg_type = "red";
    }
}

// Fetch Students
$sql = "SELECT u.id, u.name, u.email, u.created_at, s.reg_no 
        FROM users u 
        LEFT JOIN students s ON u.id = s.user_id 
        WHERE u.role = 'student' 
        ORDER BY u.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - InternSystem</title>
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
                <a href="manage_students.php" class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-purple-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-student text-xl"></i> Manage Students
                </a>
                <a href="manage_companies.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-buildings text-xl"></i> Manage Companies
                </a>
                <a href="admin_placements.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-purple-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-stamp text-xl"></i> Placements
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
                <h2 class="text-xl font-bold text-gray-800">Student Directory</h2>
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
                        <input type="text" id="searchInput" placeholder="Search by name or reg no..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <a href="add_student.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition shadow-sm">
                        <i class="ph ph-plus"></i> Add New Student
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm" id="studentTable">
                            <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Student Name</th>
                                    <th class="px-6 py-4">Registration No</th>
                                    <th class="px-6 py-4">Email Address</th>
                                    <th class="px-6 py-4 text-center">Joined Date</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 font-mono">
                                            <?php echo htmlspecialchars($row['reg_no'] ?? 'N/A'); ?>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            <?php echo htmlspecialchars($row['email']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-500">
                                            <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                    <i class="ph ph-pencil-simple text-lg"></i>
                                                </a>
                                                <a href="manage_students.php?delete_id=<?php echo $row['id']; ?>" 
                                                   onclick="return confirm('Are you sure you want to remove this student? This cannot be undone.');"
                                                   class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                    <i class="ph ph-trash text-lg"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No students found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Fallback for logo
        document.querySelector('.fallback-img').onerror = function() {
            this.style.display = 'none';
        };

        // Simple Search Filter
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#studentTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>