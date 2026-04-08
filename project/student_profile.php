<?php
session_start();
// db_connection.php ගොනුව නිවැරදිව තිබේදැයි බලන්න
include 'db_connection.php'; 

// User ID එක Session එකෙන් ලබාගන්න. නැත්නම් 1 (Testing සඳහා)
$user_id = $_SESSION['user_id'] ?? 1;

$message = ""; // පණිවිඩ පෙන්වීමට විචල්‍යයක්
$msg_type = ""; // success හෝ error

// --- දත්ත යාවත්කාලීන කිරීම (Update Logic) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $gpa = mysqli_real_escape_string($conn, $_POST['gpa']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    
    // ෆෝල්ඩරය නිර්මාණය කිරීමේ කොටස
    $upload_folder = "uploads/";
    
    // Server එකේ සම්පූර්ණ path එක
    $server_dir = __DIR__ . "/" . $upload_folder;
    
    // Folder එක නැත්නම් සාදන්න (Permissions 0777 ලෙස)
    if (!is_dir($server_dir)) {
        if (!mkdir($server_dir, 0777, true)) {
            die("Failed to create upload folder. Check permissions.");
        }
    }

    // --- 1. PROFILE PICTURE UPLOAD LOGIC ---
    $profile_pic_db_path = null; 
    
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        
        $file_ext = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_ext), $allowed_ext)) {
            // අලුත් නමක් (Unique name)
            $new_pic_name = time() . "_dp_" . $user_id . "." . $file_ext;
            
            $upload_path = $server_dir . $new_pic_name; // Server path
            $db_path_img = $upload_folder . $new_pic_name; // DB path

            // පින්තූරය upload කිරීම
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $upload_path)) {
                $profile_pic_db_path = $db_path_img;
            } else {
                $message .= "Image upload failed. Check folder permissions. ";
                $msg_type = "error";
            }
        } else {
            $message .= "Invalid image format. Only JPG, PNG, GIF allowed. ";
            $msg_type = "error";
        }
    }

    // --- 2. CV FILE UPLOAD LOGIC ---
    $cv_file_db_path = null;
    
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {
        
        $file_ext = pathinfo($_FILES["cv_file"]["name"], PATHINFO_EXTENSION);
        $new_cv_name = time() . "_cv_" . $user_id . "." . $file_ext;
        
        $upload_path_cv = $server_dir . $new_cv_name;
        $db_path_cv = $upload_folder . $new_cv_name;
        
        if (move_uploaded_file($_FILES["cv_file"]["tmp_name"], $upload_path_cv)) {
             $cv_file_db_path = $db_path_cv;
        }
    }

    // --- 3. DATABASE UPDATE/INSERT LOGIC ---
    
    // පලමුව මෙම user_id එකට අදාළව දත්ත තිබේදැයි බලන්න
    $check_query = "SELECT * FROM students WHERE user_id='$user_id'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // --- දත්ත ඇත -> UPDATE කරන්න ---
        $sql = "UPDATE students SET reg_no='$reg_no', gpa='$gpa', skills='$skills'";

        // පින්තූරයක් upload කළා නම් පමණක් update query එකට එකතු කරන්න
        if ($profile_pic_db_path != null) {
            $sql .= ", profile_pic='$profile_pic_db_path'";
        }
        // CV එකක් upload කළා නම් පමණක් update query එකට එකතු කරන්න
        if ($cv_file_db_path != null) {
            $sql .= ", cv_file='$cv_file_db_path'";
        }

        $sql .= " WHERE user_id='$user_id'";

    } else {
        // --- දත්ත නැත -> INSERT කරන්න ---
        $pic_val = ($profile_pic_db_path != null) ? "'$profile_pic_db_path'" : "NULL";
        $cv_val = ($cv_file_db_path != null) ? "'$cv_file_db_path'" : "NULL";

        $sql = "INSERT INTO students (user_id, reg_no, gpa, skills, profile_pic, cv_file) 
                VALUES ('$user_id', '$reg_no', '$gpa', '$skills', $pic_val, $cv_val)";
    }
    
    if ($conn->query($sql) === TRUE) {
        $message = "Profile Updated Successfully!";
        $msg_type = "success";
        // පිටුව refresh වීම වැළැක්වීමට redirect එකක්
        echo "<script>alert('Profile Updated Successfully!'); window.location.href = 'student_profile.php';</script>";
        exit();
    } else {
        $message = "Database Error: " . $conn->error;
        $msg_type = "error";
    }
}

// --- දත්ත පෙන්වීමට ලබා ගැනීම (Display Logic) ---
$query = "SELECT students.*, users.name, users.email FROM students RIGHT JOIN users ON students.user_id = users.id WHERE users.id = $user_id";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    $user_query = "SELECT name, email FROM users WHERE id = $user_id";
    $user_res = $conn->query($user_query);
    $user_data = $user_res->fetch_assoc();
    $data = [
        'reg_no' => '', 'gpa' => '', 'skills' => '', 'profile_pic' => '', 'cv_file' => '', 
        'name' => $user_data['name'] ?? 'User', 
        'email' => $user_data['email'] ?? ''
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - University System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white border-r border-slate-200 shadow-sm hidden md:flex flex-col z-20">
            
                <div class="p-8">
                <a href="index.php" class="block group">
                    <div class="flex items-center gap-3">
                        <img src="logo.png" alt="InternSystem Logo" class="h-10 w-auto object-contain">
                        <h1 class="text-2xl font-bold text-blue-700 tracking-tight">InternSystem</h1>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 pl-1 font-medium tracking-wide uppercase">University of Vavuniya</p>
                </a>
            </div>
            
        
            <nav class="flex-1 px-4 space-y-2 mt-2">
                <a href="student_dashboard.php" class="flex items-center gap-3 px-5 py-3.5 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all font-medium">
                    <i class="ph ph-squares-four text-xl"></i> <span>Dashboard</span>
                </a>
                <a href="student_profile.php" class="flex items-center gap-3 px-5 py-3.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200">
                    <i class="ph ph-user text-xl"></i> <span>My Profile</span>
                </a>
            </nav>
            <div class="p-6 border-t border-slate-100">
                <a href="logout.php" class="flex items-center gap-3 px-5 py-3 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition font-medium">
                    <i class="ph ph-sign-out text-xl"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <header class="bg-white border-b border-slate-200 shadow-sm h-20 flex items-center justify-between px-10 z-10">
                <h2 class="text-2xl font-bold text-slate-800">Profile Settings</h2>
                <div class="flex items-center gap-5">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($data['name']); ?></p>
                        <p class="text-xs text-slate-500"><?php echo htmlspecialchars($data['email']); ?></p>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 lg:p-10">
                <div class="max-w-5xl mx-auto">
                    
                    <?php if(!empty($message)): ?>
                        <div class="mb-6 p-4 rounded-xl <?php echo $msg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="student_profile.php" method="POST" enctype="multipart/form-data">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            
                            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-8 py-10 text-white">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                    <div class="w-28 h-28 bg-white p-1 rounded-full shadow-lg relative group">
                                        
                                        <div class="w-full h-full bg-slate-100 rounded-full flex items-center justify-center text-slate-400 overflow-hidden relative">
                                            <?php 
                                                $pic_path = $data['profile_pic'];
                                                $show_img = (!empty($pic_path) && file_exists(__DIR__ . "/" . $pic_path));
                                            ?>
                                            
                                            <img id="profilePreview" 
                                                 src="<?php echo $show_img ? $pic_path . '?v=' . time() : 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='; ?>" 
                                                 class="<?php echo $show_img ? '' : 'hidden'; ?> w-full h-full object-cover z-10">
                                            
                                            <i id="defaultIcon" class="ph-fill ph-user text-5xl absolute <?php echo $show_img ? 'hidden' : ''; ?>"></i>
                                        </div>

                                        <label for="profile_upload" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full border-4 border-white hover:bg-blue-700 transition shadow-sm cursor-pointer z-20">
                                            <i class="ph-bold ph-camera text-sm"></i>
                                            <input type="file" id="profile_upload" name="profile_pic" class="hidden" accept="image/*" onchange="previewImage(this)">
                                        </label>

                                    </div>
                                    <div class="text-center md:text-left">
                                        <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($data['name']); ?></h1>
                                        <p class="text-blue-100 text-sm mt-1 font-medium">Update your student information</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 lg:p-10">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                    
                                    <div class="col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Registration Number</label>
                                        <input type="text" name="reg_no" value="<?php echo htmlspecialchars($data['reg_no']); ?>" 
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50">
                                    </div>

                                    <div class="col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Current GPA</label>
                                        <input type="number" step="0.01" max="4.00" name="gpa" value="<?php echo htmlspecialchars($data['gpa']); ?>" 
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Skills</label>
                                        <textarea name="skills" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50"><?php echo htmlspecialchars($data['skills']); ?></textarea>
                                    </div>

                                    <div class="md:col-span-2 border-t border-dashed border-slate-300 pt-8 mt-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Upload CV / Resume</label>
                                        <div class="flex items-center justify-center w-full">
                                            <label for="cv_file_upload" class="flex flex-col items-center justify-center w-full h-40 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-blue-50 hover:bg-blue-100 transition duration-300 group">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="ph-fill ph-cloud-arrow-up text-3xl text-blue-600 mb-2"></i>
                                                    <p class="mb-2 text-sm text-slate-600"><span class="font-bold text-blue-600">Click to upload CV</span></p>
                                                    <p class="text-xs text-slate-500">PDF or DOCX</p>
                                                </div>
                                                <input id="cv_file_upload" type="file" name="cv_file" class="hidden" accept=".pdf,.doc,.docx" />
                                            </label>
                                        </div>
                                        
                                        <?php if(!empty($data['cv_file'])): ?>
                                            <div class="mt-4 text-green-600 text-sm font-medium">
                                                <i class="ph-fill ph-check-circle"></i> CV Currently Uploaded: 
                                                <a href="<?php echo $data['cv_file']; ?>" target="_blank" class="underline font-bold">View Document</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <div class="flex justify-end gap-4 mt-10 pt-6 border-t border-slate-100">
                                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold shadow-lg transition">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('profilePreview');
                    var icon = document.getElementById('defaultIcon');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if(icon) icon.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>