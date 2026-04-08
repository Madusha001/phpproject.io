<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Securely check credentials
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Password verification
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($role == 'student') header("Location: student_dashboard.php");
            elseif ($role == 'company') header("Location: company_dashboard.php");
            elseif ($role == 'admin') header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "User not found or role mismatch.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InternSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Radio Button Styling */
        .role-radio:checked + div {
            border-color: #2563EB;
            background-color: #EFF6FF;
            color: #2563EB;
        }
        .role-radio:checked + div .check-icon {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="logo.png" alt="InternSystem Logo" class="h-12 w-auto object-contain">
                     
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
            <p class="text-sm text-gray-500 mt-1">University of Vavuniya Internship Portal</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md flex items-center">
                <i class="ph ph-warning-circle text-red-500 text-xl mr-2"></i>
                <p class="text-red-700 text-sm font-medium"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php" class="space-y-6">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="ph ph-envelope"></i>
                    </div>
                    <input type="email" name="email" required 
                        class="pl-10 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition sm:text-sm" 
                        placeholder="Enter your email">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="ph ph-lock"></i>
                    </div>
                    <input type="password" name="password" required 
                        class="pl-10 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition sm:text-sm" 
                        placeholder="••••••••">
                </div>
                <div class="flex justify-end mt-1">
                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="student" checked class="role-radio hidden">
                        <div class="border border-gray-200 rounded-lg p-3 text-center hover:bg-gray-50 transition relative">
                            <i class="ph ph-student text-xl mb-1 block"></i>
                            <span class="text-xs font-semibold">Student</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="company" class="role-radio hidden">
                        <div class="border border-gray-200 rounded-lg p-3 text-center hover:bg-gray-50 transition relative">
                            <i class="ph ph-briefcase text-xl mb-1 block"></i>
                            <span class="text-xs font-semibold">Company</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="admin" class="role-radio hidden">
                        <div class="border border-gray-200 rounded-lg p-3 text-center hover:bg-gray-50 transition relative">
                            <i class="ph ph-shield-check text-xl mb-1 block"></i>
                            <span class="text-xs font-semibold">Admin</span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Sign In
            </button>

            <div class="text-center text-sm text-gray-600">
                Don't have an account? 
                <a href="register.php" class="font-bold text-blue-600 hover:text-blue-500 transition">Register here</a>
            </div>
        </form>

        <div class="mt-8 border-t border-gray-100 pt-6">
            <p class="text-center text-xs text-gray-400">&copy; 2026 University of Vavuniya. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Logo එක load වෙන්නේ නැත්නම් Icon එකක් පෙන්වන්න
        document.querySelector('.fallback-img').onerror = function() {
            this.style.display = 'none';
            this.parentElement.innerHTML = '<div class="h-20 w-20 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-3xl mx-auto mb-2"><i class="ph ph-graduation-cap"></i></div>';
        };
    </script>
</body>
</html>