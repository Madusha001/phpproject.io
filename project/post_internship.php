<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];

    // Using prepared statements for security
    $stmt = $conn->prepare("INSERT INTO internships (company_id, title, type, duration, description, requirements, status, posted_date) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())");
    $stmt->bind_param("isssss", $company_id, $title, $type, $duration, $description, $requirements);
    
    if($stmt->execute()) {
        echo "<script>alert('Internship Published Successfully!'); window.location.href='company_dashboard.php';</script>";
    } else {
        $error = "Error posting internship: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Internship - InternSystem</title>
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
                <a href="post_internship.php" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg font-semibold transition-colors">
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
                <div class="flex items-center gap-2 text-gray-500 text-sm">
                    <a href="company_dashboard.php" class="hover:text-green-600 transition">Dashboard</a>
                    <i class="ph ph-caret-right text-xs"></i>
                    <span class="text-gray-800 font-semibold">Post Internship</span>
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

            <div class="p-8 max-w-4xl mx-auto">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Create New Internship</h2>
                    <p class="text-gray-500 text-sm mt-1">Fill in the details below to post a new opportunity for students.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <form action="post_internship.php" method="POST" class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Job Role / Title</label>
                            <input type="text" name="title" required placeholder="e.g. Software Engineer Intern"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition shadow-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Work Type</label>
                                <div class="relative">
                                    <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 appearance-none focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-white">
                                        <option>On-site</option>
                                        <option>Remote</option>
                                        <option>Hybrid</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                                <div class="relative">
                                    <select name="duration" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 appearance-none focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-white">
                                        <option>3 Months</option>
                                        <option>6 Months</option>
                                        <option>12 Months</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
                            <textarea name="description" rows="5" required placeholder="Describe the responsibilities and what the intern will learn..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requirements & Skills</label>
                            <textarea name="requirements" rows="4" required placeholder="e.g. Basic knowledge of PHP, MySQL, Team player..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition shadow-sm"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="company_dashboard.php" class="px-6 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-lg transition">Cancel</a>
                            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <i class="ph ph-paper-plane-right"></i> Publish Internship
                            </button>
                        </div>

                    </form>
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