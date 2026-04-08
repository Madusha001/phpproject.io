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

// --- Dashboard Statistics ---
// 1. Get Total Internships posted
$count_sql = "SELECT COUNT(*) as total FROM internships WHERE company_id = $company_id";
$count_result = $conn->query($count_sql);
$total_posts = $count_result->fetch_assoc()['total'];

// 2. Get Active Internships
$active_sql = "SELECT COUNT(*) as active FROM internships WHERE company_id = $company_id AND status = 'active'";
$active_result = $conn->query($active_sql);
$active_posts = $active_result->fetch_assoc()['active'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - InternSystem</title>
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
                <a href="company_dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg font-semibold transition-colors">
                    <i class="ph ph-squares-four text-xl"></i> 
                    Dashboard
                </a>
                <a href="post_internship.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg font-medium transition-colors">
                    <i class="ph ph-plus-circle text-xl"></i> 
                    Post Internship
                </a>
                <a href="company_applicants.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg font-medium transition-colors">
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
                <h2 class="text-xl font-bold text-gray-800">Dashboard Overview</h2>
                
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
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-briefcase"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Posted</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $total_posts; ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Active Listings</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo $active_posts; ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-2xl">
                            <i class="ph ph-users-three"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Applicants</p>
                            <h3 class="text-2xl font-bold text-gray-900">-</h3> </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-800">Recent Job Postings</h3>
                        <a href="post_internship.php" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="ph ph-plus-bold"></i> Post New
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-gray-500 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Role Title</th>
                                    <th class="px-6 py-4 font-semibold">Type</th>
                                    <th class="px-6 py-4 font-semibold">Duration</th>
                                    <th class="px-6 py-4 font-semibold">Posted Date</th>
                                    <th class="px-6 py-4 font-semibold">Status</th>
                                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                $sql = "SELECT * FROM internships WHERE company_id = ? ORDER BY posted_date DESC LIMIT 10";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $company_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $statusClass = $row['status'] == 'active' 
                                            ? 'bg-green-100 text-green-700 border-green-200' 
                                            : 'bg-gray-100 text-gray-600 border-gray-200';
                                        
                                        // Format date nicely
                                        $date = date("M d, Y", strtotime($row['posted_date']));

                                        echo "<tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='px-6 py-4 font-medium text-gray-900'>{$row['title']}</td>
                                            <td class='px-6 py-4 text-gray-500'>{$row['type']}</td>
                                            <td class='px-6 py-4 text-gray-500'>{$row['duration']}</td>
                                            <td class='px-6 py-4 text-gray-500'>{$date}</td>
                                            <td class='px-6 py-4'>
                                                <span class='px-2.5 py-1 $statusClass border rounded-full text-xs font-semibold'>".ucfirst($row['status'])."</span>
                                            </td>
                                            <td class='px-6 py-4 text-right'>
                                                <button class='text-gray-400 hover:text-blue-600 transition'><i class='ph ph-pencil-simple text-lg'></i></button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='px-6 py-12 text-center text-gray-500'>
                                        <div class='flex flex-col items-center justify-center'>
                                            <i class='ph ph-files text-4xl text-gray-300 mb-2'></i>
                                            <p>No internships posted yet.</p>
                                            <a href='post_internship.php' class='mt-2 text-green-600 font-medium hover:underline'>Create your first post</a>
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
            // Show a text fallback if image fails
            const fallback = document.createElement('div');
            fallback.className = 'text-center mb-2';
            fallback.innerHTML = '<i class="ph ph-buildings text-4xl text-green-600"></i><h1 class="font-bold text-gray-800 mt-1">InternSystem</h1>';
            this.parentElement.insertBefore(fallback, this.nextSibling);
        };
    </script>
</body>
</html>