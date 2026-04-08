<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Internship Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #EFF6FF 0%, #FFFFFF 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="glass-nav fixed w-full top-0 z-50 border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-3">
                    <img src="logo.png" alt="InternSystem Logo" class="h-12 w-auto object-contain">
                    
                    <div class="flex flex-col leading-tight">
                        <span class="text-xl font-bold text-gray-900 tracking-tight">InternSystem</span>
                        <span class="text-xs text-blue-600 font-semibold uppercase tracking-wider">University of Vavuniya</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600 font-medium transition">Home</a>
                    <a href="about.php" class="text-gray-600 hover:text-blue-600 font-medium transition">About</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium transition">Contact</a>
                    
                    <div class="flex items-center gap-3 ml-4">
                        <a href="login.php" class="px-5 py-2.5 text-blue-600 font-bold hover:bg-blue-50 rounded-full transition text-sm">Log In</a>
                        <a href="register.php" class="px-5 py-2.5 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-0.5 text-sm">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 hero-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left z-10">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mb-6">
                        <span class="flex h-2 w-2 bg-blue-600 rounded-full mr-2"></span> New Academic Year 2026
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Streamline Your <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Internship Journey</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Connecting the brightest students of the University of Vavuniya with industry leaders. Manage applications, digital logbooks, and evaluations seamlessly in one platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="register.php" class="px-8 py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-xl shadow-blue-200 transition transform hover:-translate-y-1 text-center">
                            Get Started
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 transition text-center flex items-center justify-center gap-2">
                            <i class="ph ph-info"></i> Learn More
                        </a>
                    </div>
                </div>
                <div class="relative lg:block">
                    <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                    <div class="absolute -bottom-8 -left-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80" alt="Students working" class="relative rounded-2xl shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500 object-cover h-[500px] w-full">
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-blue-600 font-bold tracking-wide uppercase text-sm mb-2">Our Features</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900">Everything you need to manage internships</h3>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">A centralized platform designed to bridge the gap between students, companies, and the university administration.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <i class="ph ph-student text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For Students</h3>
                    <p class="text-gray-600 leading-relaxed">Create your profile, apply for top internships, maintain digital logbooks, and track your evaluation progress in real-time.</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-100 transition duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition duration-300">
                        <i class="ph ph-buildings text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For Companies</h3>
                    <p class="text-gray-600 leading-relaxed">Post internship vacancies, review student applications, approve logbooks, and submit final performance evaluations.</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-purple-100 transition duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <i class="ph ph-bank text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For University</h3>
                    <p class="text-gray-600 leading-relaxed">Monitor student placement status, generate detailed reports, and streamline the entire internship management process.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <img src="logo.png" alt="InternSystem Logo" class="h-10 w-auto object-contain bg-white rounded p-1">
                        <div>
                            <span class="block text-xl font-bold text-white">InternSystem</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">University of Vavuniya</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed mb-6">
                        Bridging the gap between academia and industry. Our platform streamlines the internship process for students, companies, and faculty.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition duration-300"><i class="ph ph-facebook-logo text-xl"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 hover:text-white transition duration-300"><i class="ph ph-twitter-logo text-xl"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-700 hover:text-white transition duration-300"><i class="ph ph-linkedin-logo text-xl"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition duration-300"><i class="ph ph-instagram-logo text-xl"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Quick Links</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="index.php" class="hover:text-blue-400 transition duration-300 flex items-center gap-2"><i class="ph ph-caret-right"></i> Home</a></li>
                        <li><a href="login.php" class="hover:text-blue-400 transition duration-300 flex items-center gap-2"><i class="ph ph-caret-right"></i> Student Login</a></li>
                        <li><a href="register.php" class="hover:text-blue-400 transition duration-300 flex items-center gap-2"><i class="ph ph-caret-right"></i> Register Company</a></li>
                        <li><a href="#features" class="hover:text-blue-400 transition duration-300 flex items-center gap-2"><i class="ph ph-caret-right"></i> Features</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Resources</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition duration-300">Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition duration-300">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition duration-300">Terms of Service</a></li>
                        <li><a href="https://vau.ac.lk" target="_blank" class="hover:text-blue-400 transition duration-300">University Website</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Contact Us</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="ph ph-map-pin text-xl text-blue-500 mt-1"></i>
                            <span>Pampaimadu,<br>Vavuniya, Sri Lanka</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph ph-envelope text-xl text-blue-500"></i>
                            <span>info@vau.ac.lk</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph ph-phone text-xl text-blue-500"></i>
                            <span>+94 24 222 3333</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; 2026 Student Internship Management System. All rights reserved.</p>
                <p>Designed & Developed by <span class="text-white font-semibold">Group 30 Project Team</span></p>
            </div>
        </div>
    </footer>

</body>
</html>