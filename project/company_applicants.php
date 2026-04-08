<?php
session_start();
include 'db_connection.php';

// Check if user is logged in as a company
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['name'];

// Handle Status Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $status = $_GET['action']; // 'accepted' or 'rejected'
    $app_id = intval($_GET['id']); // Sanitize ID

    // Security Check: Ensure this application belongs to an internship by THIS company
    // This prevents companies from modifying other companies' applicants
    $check_sql = "SELECT a.id FROM applications a 
                  JOIN internships i ON a.internship_id = i.id 
                  WHERE a.id = ? AND i.company_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $app_id, $company_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $app_id);
        $stmt->execute();
        $stmt->close();
    }
    $check_stmt->close();
    
    // Redirect to clear query params
    header("Location: company_applicants.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants - InternSystem</title>
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
                <span class="text-xs font-bold text-green-500 uppercase tracking-wider">Company Portal</span>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-6">
                <a href="company_dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-squares-four text-xl"></i> 
                    Dashboard
                </a>
                <a href="post_internship.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-plus-circle text-xl"></i> 
                    Post Internship
                </a>
                <a href="company_applicants.php" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-users text-xl"></i> 
                    Applicants
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
                <div class="flex items-center gap-2 text-gray-500 text-sm">
                    <a href="company_dashboard.php" class="hover:text-green-600 transition">Dashboard</a>
                    <i class="ph ph-caret-right text-xs"></i>
                    <span class="text-gray-800 font-semibold">Manage Applicants</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($company_name); ?></p>
                        <p class="text-xs text-green-600">Company Account</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold border border-green-200">
                        <?php echo substr($company_name, 0, 1); ?>
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">
                
                <div class="mb-6 flex justify-between items-end">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Applicants</h2>
                        <p class="text-gray-500 text-sm mt-1">Review and manage student applications.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Student Name</th>
                                    <th class="px-6 py-4 font-semibold">Role Applied For</th>
                                    <th class="px-6 py-4 font-semibold">GPA</th>
                                    <th class="px-6 py-4 font-semibold">Current Status</th>
                                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                $sql = "SELECT a.id as app_id, u.name, i.title, s.gpa, a.status 
                                        FROM applications a 
                                        JOIN users u ON a.student_id = u.id 
                                        JOIN internships i ON a.internship_id = i.id 
                                        JOIN students s ON u.id = s.user_id
                                        WHERE i.company_id = ?
                                        ORDER BY a.id DESC";
                                
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $company_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        // Badge Colors based on Status
                                        $statusClass = 'bg-gray-100 text-gray-600'; // Default pending
                                        if($row['status'] == 'accepted') $statusClass = 'bg-green-100 text-green-700 border-green-200';
                                        if($row['status'] == 'rejected') $statusClass = 'bg-red-50 text-red-600 border-red-100';

                                        echo "<tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='px-6 py-4'>
                                                <div class='flex items-center gap-3'>
                                                    <div class='w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold'>
                                                        ".substr($row['name'], 0, 1)."
                                                    </div>
                                                    <span class='font-medium text-gray-900'>{$row['name']}</span>
                                                </div>
                                            </td>
                                            <td class='px-6 py-4 text-gray-500'>{$row['title']}</td>
                                            <td class='px-6 py-4 text-gray-500 font-medium'>{$row['gpa']}</td>
                                            <td class='px-6 py-4'>
                                                <span class='px-2.5 py-1 $statusClass border rounded-full text-xs font-semibold capitalize'>
                                                    {$row['status']}
                                                </span>
                                            </td>
                                            <td class='px-6 py-4 text-right'>
                                                <div class='flex items-center justify-end gap-2'>
                                                    <a href='company_applicants.php?action=accepted&id={$row['app_id']}' 
                                                       class='p-2 text-green-600 hover:bg-green-50 rounded-lg transition tooltip' title='Accept'>
                                                        <i class='ph ph-check-circle text-xl'></i>
                                                    </a>
                                                    <a href='company_applicants.php?action=rejected&id={$row['app_id']}' 
                                                       class='p-2 text-red-500 hover:bg-red-50 rounded-lg transition tooltip' title='Reject'>
                                                        <i class='ph ph-x-circle text-xl'></i>
                                                    </a>
                                                    <a href='#' class='p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition' title='View Profile'>
                                                        <i class='ph ph-file-text text-xl'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='px-6 py-12 text-center text-gray-500'>
                                        <div class='flex flex-col items-center justify-center'>
                                            <i class='ph ph-users-three text-4xl text-gray-300 mb-2'></i>
                                            <p>No applications received yet.</p>
                                        </div>
                                    </td></tr>";
                                }
                                $stmt->close();
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
            fallback.innerHTML = '<i class="ph ph-buildings text-4xl text-green-600"></i><h1 class="font-bold text-gray-800 mt-1">InternSystem</h1>';
            this.parentElement.insertBefore(fallback, this.nextSibling);
        };
    </script>
</body>
</html>