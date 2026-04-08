<?php
session_start();
include 'db_connection.php';

// සිසුවෙකු ලෙස Login වී ඇත්දැයි පරීක්ෂා කිරීම
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    echo "<script>alert('Please login as a student first.'); window.location.href='login.php';</script>";
    exit();
}

$student_id = $_SESSION['user_id'];
$internship_id = "";
$internship_title = "";
$company_name = "";
$error = "";

// 1. පිටුවට එන විට URL එකේ ID එක තිබේදැයි බැලීම (GET Request)
if (isset($_GET['id'])) {
    $internship_id = intval($_GET['id']); // ID එක ආරක්ෂිතව integer බවට පත් කරයි

    // එම ID එකට අදාළ Internship එක Database එකේ තිබේදැයි පරීක්ෂා කිරීම
    $check_sql = "SELECT i.title, u.name FROM internships i JOIN users u ON i.company_id = u.id WHERE i.id = $internship_id";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $internship_title = $row['title'];
        $company_name = $row['name'];
    } else {
        $error = "Invalid Internship ID selected.";
    }
}

// 2. Form එක Submit කළ විට (POST Request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $internship_id = $_POST['internship_id'];
    $cover_letter = $_POST['cover_letter'];

    // ID එක වලංගු දැයි නැවත පරීක්ෂා කිරීම
    $check_validity = $conn->query("SELECT id FROM internships WHERE id = '$internship_id'");

    if ($check_validity->num_rows > 0) {
        // දැනටමත් Apply කර ඇත්දැයි බැලීම
        $check_applied = $conn->query("SELECT id FROM applications WHERE internship_id = '$internship_id' AND student_id = '$student_id'");
        
        if ($check_applied->num_rows == 0) {
            // දත්ත ඇතුළත් කිරීම (Insert)
            $stmt = $conn->prepare("INSERT INTO applications (internship_id, student_id, cover_letter) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $internship_id, $student_id, $cover_letter);
            
            if($stmt->execute()) {
                echo "<script>alert('Application Submitted Successfully!'); window.location.href='student_dashboard.php';</script>";
                exit();
            } else {
                $error = "Error submitting application: " . $conn->error;
            }
        } else {
            $error = "You have already applied for this internship.";
        }
    } else {
        $error = "Cannot find the internship. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Internship - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r shadow-sm hidden md:flex flex-col">
            <div class="p-6">
                <a href="student_dashboard.php" class="block group">
                    <h1 class="text-2xl font-bold text-blue-600 flex items-center gap-2"><i class="ph ph-graduation-cap"></i> InternSystem</h1>
                </a>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="student_dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition"><i class="ph ph-squares-four text-xl"></i> Dashboard</a>
                <a href="internships.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition"><i class="ph ph-briefcase text-xl"></i> Browse Internships</a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Apply for Internship</h2>
            
            <div class="bg-white max-w-2xl mx-auto rounded-xl shadow-sm border border-gray-200 p-8">
                
                <?php if($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if($internship_title): ?>
                    <div class="mb-6 border-b pb-4">
                        <p class="text-sm text-gray-500">You are applying for:</p>
                        <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($internship_title); ?></h3>
                        <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($company_name); ?></p>
                    </div>

                    <form action="apply_internship.php" method="POST">
                        <input type="hidden" name="internship_id" value="<?php echo $internship_id; ?>">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <textarea name="cover_letter" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Explain why you are the best fit for this role..."></textarea>
                        </div>

                        <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                            <a href="internships.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</a>
                            <button type="submit" class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md flex items-center gap-2">
                                <i class="ph ph-paper-plane-tilt"></i> Submit Application
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">Please select an internship first.</p>
                        <a href="internships.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Go to Internships</a>
                    </div>
                <?php endif; ?>
                
            </div>
        </main>
    </div>
</body>
</html>