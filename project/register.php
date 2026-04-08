<?php
session_start();
include 'db_connection.php';

// ---------------------------------------------------------
// මෙන්න Admin Secret Key එක. ඔයාට කැමති එකක් මෙතන දාන්න.
// දැනට දාලා තියෙන්නේ "Admin123" කියලයි.
// ---------------------------------------------------------
$admin_secret_key = "Admin123"; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; 

    // Admin Verification
    if ($role == 'admin') {
        $input_key = $_POST['secret_key'];
        
        // Key එක match වෙනවාදැයි බැලීම
        if ($input_key !== $admin_secret_key) {
            echo "<script>alert('Invalid Admin Secret Key! Registration Denied.'); window.history.back();</script>";
            exit();
        }
    }

    // Check email
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You are already registered! Please Login.'); window.history.back();</script>";
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $last_id = $conn->insert_id;
            
            if ($role == 'student') {
                $conn->query("INSERT INTO students (user_id) VALUES ('$last_id')");
            }
            
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Internship System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-btn { transition: all 0.3s ease; }
        .active-student { border-bottom: 2px solid #2563EB; color: #2563EB; font-weight: 600; }
        .active-company { border-bottom: 2px solid #16A34A; color: #16A34A; font-weight: 600; }
        .active-admin { border-bottom: 2px solid #DC2626; color: #DC2626; font-weight: 600; }
        .inactive-tab { color: #6B7280; border-bottom: 2px solid transparent; }
        .inactive-tab:hover { color: #374151; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create Account</h2>
            <p class="mt-2 text-sm text-gray-500">University of Vavuniya Internship Portal</p>
        </div>

        <div class="flex justify-center border-b border-gray-200 mb-8">
            <button id="tabStudent" onclick="switchTab('student')" class="tab-btn active-student px-4 py-3 w-1/3 text-center">Student</button>
            <button id="tabCompany" onclick="switchTab('company')" class="tab-btn inactive-tab px-4 py-3 w-1/3 text-center">Company</button>
            <button id="tabAdmin" onclick="switchTab('admin')" class="tab-btn inactive-tab px-4 py-3 w-1/3 text-center">Admin</button>
        </div>

        <form id="studentForm" method="POST" action="register.php" class="space-y-5">
            <input type="hidden" name="role" value="student">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-user"></i></div>
                    <input type="text" name="name" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="John Doe">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">University Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-envelope"></i></div>
                    <input type="email" name="email" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="student@vau.ac.lk">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-lock"></i></div>
                    <input type="password" name="password" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Register as Student
            </button>
        </form>

        <form id="companyForm" method="POST" action="register.php" class="hidden space-y-5">
            <input type="hidden" name="role" value="company">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-buildings"></i></div>
                    <input type="text" name="name" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Tech Solutions Ltd">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-envelope"></i></div>
                    <input type="email" name="email" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="hr@company.com">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-lock"></i></div>
                    <input type="password" name="password" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Register as Company
            </button>
        </form>

        <form id="adminForm" method="POST" action="register.php" class="hidden space-y-5">
            <input type="hidden" name="role" value="admin">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-user-gear"></i></div>
                    <input type="text" name="name" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="System Administrator">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-envelope"></i></div>
                    <input type="email" name="email" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="admin@vau.ac.lk">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-key"></i></div>
                    <input type="text" name="secret_key" required class="pl-10 block w-full px-3 py-2 border border-red-300 bg-red-50 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter Admin Key">
                </div>
                
                <p class="text-xs text-red-600 mt-2 font-semibold bg-red-100 p-2 rounded border border-red-200">
                    🔔 TEST MODE: The Secret Key is: <span class="font-bold underline"><?php echo $admin_secret_key; ?></span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="ph ph-lock"></i></div>
                    <input type="password" name="password" required class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                Register as Admin
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Already have an account?</span>
            <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">Sign in here</a>
        </div>
    </div>

    <script>
        function switchTab(role) {
            const studentForm = document.getElementById('studentForm');
            const companyForm = document.getElementById('companyForm');
            const adminForm = document.getElementById('adminForm');
            
            const tabStudent = document.getElementById('tabStudent');
            const tabCompany = document.getElementById('tabCompany');
            const tabAdmin = document.getElementById('tabAdmin');

            // Reset tabs
            tabStudent.className = "tab-btn inactive-tab px-4 py-3 w-1/3 text-center";
            tabCompany.className = "tab-btn inactive-tab px-4 py-3 w-1/3 text-center";
            tabAdmin.className = "tab-btn inactive-tab px-4 py-3 w-1/3 text-center";

            // Hide forms
            studentForm.classList.add('hidden');
            companyForm.classList.add('hidden');
            adminForm.classList.add('hidden');

            // Activate tab
            if (role === 'student') {
                studentForm.classList.remove('hidden');
                tabStudent.className = "tab-btn active-student px-4 py-3 w-1/3 text-center";
            } else if (role === 'company') {
                companyForm.classList.remove('hidden');
                tabCompany.className = "tab-btn active-company px-4 py-3 w-1/3 text-center";
            } else if (role === 'admin') {
                adminForm.classList.remove('hidden');
                tabAdmin.className = "tab-btn active-admin px-4 py-3 w-1/3 text-center";
            }
        }
    </script>
</body>
</html>