<?php
session_start();
include 'db_connection.php';
$company_id = $_SESSION['user_id'] ?? 2; // Default for demo

// Get student info from URL
$student_id = $_GET['student_id'] ?? 0;
if ($student_id) {
    $s_res = $conn->query("SELECT name FROM users WHERE id = $student_id");
    $student_name = ($s_res->num_rows > 0) ? $s_res->fetch_assoc()['name'] : "Unknown Student";
} else {
    $student_name = "Select a student first";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_id = $_POST['student_id'];
    $rec = $_POST['recommendation'];
    $comm = $_POST['comments'];

    $stmt = $conn->prepare("INSERT INTO evaluations (student_id, company_id, recommendation, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $s_id, $company_id, $rec, $comm);
    
    if ($stmt->execute()) {
        echo "<script>alert('Evaluation Submitted!'); window.location.href='company_dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Evaluate Student - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-white border-r shadow-sm hidden md:flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-green-600"><i class="ph ph-buildings"></i> InternSystem</h1>
            </div>
             <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="company_dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg transition"><i class="ph ph-house text-xl"></i> Dashboard</a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Evaluate Intern</h2>
            
            <div class="bg-white max-w-2xl mx-auto rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="mb-6 border-b pb-4">
                    <p class="text-sm text-gray-500">Evaluating Student:</p>
                    <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($student_name); ?></h3>
                </div>

                <form method="POST" action="evaluate_student.php">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Performance Comments</label>
                        <textarea name="comments" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-2 outline-none focus:ring-green-500" placeholder="Describe the student's performance..."></textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Final Recommendation</label>
                        <select name="recommendation" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-green-500 outline-none">
                            <option>Highly Recommended</option>
                            <option>Recommended</option>
                            <option>Needs Improvement</option>
                            <option>Not Recommended</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-4 border-t pt-6">
                        <a href="company_dashboard.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-md">
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>