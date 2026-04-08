<?php
session_start();
include 'db_connection.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);

    // 1. මුලින්ම 'users' table එකට දත්ත ඇතුළත් කිරීම
    $sql_user = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'student')";
    
    if ($conn->query($sql_user) === TRUE) {
        $last_id = $conn->insert_id; // අලුතින් හැදුණු User ID එක

        // 2. දැන් 'students' table එකට Registration No එක ඇතුළත් කිරීම
        $sql_student = "INSERT INTO students (user_id, reg_no) VALUES ('$last_id', '$reg_no')";
        
        if ($conn->query($sql_student) === TRUE) {
            echo "<script>alert('Student Added Successfully!'); window.location.href = 'manage_students.php';</script>";
            exit();
        }
    } else {
        $message = "Error: " . $conn->error;
        $msg_type = "red";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-purple-600 p-6 text-white text-center">
            <h2 class="text-2xl font-bold">Add New Student</h2>
            <p class="text-purple-100 text-sm">Create a new student account</p>
        </div>

        <form action="add_student.php" method="POST" class="p-8 space-y-5">
            <?php if($message): ?>
                <div class="p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?php echo $message; ?></div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Registration Number</label>
                <input type="text" name="reg_no" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Temporary Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div class="flex gap-3 pt-2">
                <a href="manage_students.php" class="flex-1 text-center py-2.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 font-medium transition">Cancel</a>
                <button type="submit" class="flex-1 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-bold shadow-md transition">Register</button>
            </div>
        </form>
    </div>

</body>
</html>