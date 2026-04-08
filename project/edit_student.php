<?php
session_start();
include 'db_connection.php';

// Auth Check - Admin ද යන්න පරීක්ෂා කිරීම
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$msg_type = "";

// 1. URL එකෙන් ID එක ලබා ගැනීම
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // වත්මන් දත්ත ලබා ගැනීම (Join users and students)
    $sql = "SELECT u.name, u.email, s.reg_no FROM users u 
            LEFT JOIN students s ON u.id = s.user_id 
            WHERE u.id = $user_id AND u.role = 'student'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Student not found.");
    }
} else {
    header("Location: manage_students.php");
    exit();
}

// 2. Form එක Submit කළ විට දත්ත Update කිරීම
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);

    // Users table එක Update කිරීම
    $update_user = "UPDATE users SET name='$name', email='$email' WHERE id=$user_id";
    
    if ($conn->query($update_user) === TRUE) {
        // Students table එකේ reg_no Update කිරීම
        $update_student = "UPDATE students SET reg_no='$reg_no' WHERE user_id=$user_id";
        
        if ($conn->query($update_student) === TRUE) {
            echo "<script>alert('Student Updated Successfully!'); window.location.href = 'manage_students.php';</script>";
            exit();
        }
    } else {
        $message = "Error updating record: " . $conn->error;
        $msg_type = "red";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 p-6 text-white text-center">
            <h2 class="text-2xl font-bold">Edit Student Details</h2>
            <p class="text-blue-100 text-sm">Update information for <?php echo htmlspecialchars($data['name']); ?></p>
        </div>

        <form action="edit_student.php?id=<?php echo $user_id; ?>" method="POST" class="p-8 space-y-5">
            <?php if($message): ?>
                <div class="p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?php echo $message; ?></div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Registration Number</label>
                <input type="text" name="reg_no" value="<?php echo htmlspecialchars($data['reg_no']); ?>" required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex gap-3 pt-2">
                <a href="manage_students.php" class="flex-1 text-center py-2.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md transition">
                    Update Changes
                </button>
            </div>
        </form>
    </div>

</body>
</html>