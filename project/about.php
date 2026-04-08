<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Internship Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .blob-bg {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23EBF4FF' d='M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.7C91.4,-34.4,98.1,-19.7,95.8,-4.8C93.5,10.1,82.2,25.2,70.8,38.8C59.4,52.4,47.9,64.5,34.4,72.7C20.9,80.9,5.4,85.2,-8.7,82.4C-22.8,79.6,-35.5,69.7,-47.6,59.3C-59.7,48.9,-71.2,38,-78.6,24.8C-86,11.6,-89.3,-3.9,-84.9,-17.6C-80.5,-31.3,-68.4,-43.2,-55.4,-50.8C-42.4,-58.4,-28.5,-61.7,-15.1,-64.5C-1.7,-67.3,11.7,-69.6,25.2,-71.8L44.7,-76.4Z' transform='translate(100 100)' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right center;
            background-size: contain;
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
                    <a href="about.php" class="text-blue-600 font-bold transition">About</a>
                    <a href="index.php#footer" class="text-gray-600 hover:text-blue-600 font-medium transition">Contact</a>
                    <div class="flex items-center gap-3 ml-4">
                        <a href="login.php" class="px-5 py-2.5 text-blue-600 font-bold hover:bg-blue-50 rounded-full transition text-sm">Log In</a>
                        <a href="register.php" class="px-5 py-2.5 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 shadow-lg transition text-sm">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-36 pb-20 bg-white blob-bg overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="z-10">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-sm font-bold mb-6 border border-blue-100">
                        🚀 Empowering the Future Workforce
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Digitizing the <br>
                        <span class="text-blue-600">Internship Experience</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        The University of Vavuniya introduces a state-of-the-art platform to bridge the gap between academic learning and professional industry experience. No more paperwork—just seamless connections.
                    </p>
                    <div class="flex gap-4">
                        <a href="#story" class="px-6 py-3 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition">Our Story</a>
                        <a href="register.php" class="px-6 py-3 bg-white text-blue-600 border border-blue-200 rounded-lg font-semibold hover:border-blue-600 transition">Join Us</a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80" alt="Team meeting" class="relative rounded-2xl shadow-2xl border-4 border-white transform hover:rotate-1 transition duration-500">
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-600 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <h3 class="text-4xl font-bold">500+</h3>
                    <p class="text-blue-100 text-sm uppercase tracking-wide mt-1">Students</p>
                </div>
                <div>
                    <h3 class="text-4xl font-bold">50+</h3>
                    <p class="text-blue-100 text-sm uppercase tracking-wide mt-1">Companies</p>
                </div>
                <div>
                    <h3 class="text-4xl font-bold">100%</h3>
                    <p class="text-blue-100 text-sm uppercase tracking-wide mt-1">Paperless</p>
                </div>
                <div>
                    <h3 class="text-4xl font-bold">24/7</h3>
                    <p class="text-blue-100 text-sm uppercase tracking-wide mt-1">Access</p>
                </div>
            </div>
        </div>
    </section>

    <section id="story" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Why we built InternSystem?</h2>
                <p class="text-gray-500 mt-4 text-lg">Managing internships manually was a headache for everyone. We decided to change that.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                            <i class="ph ph-x-circle text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">The Old Way (Manual)</h4>
                            <p class="text-gray-600 mt-2">Students carried physical logbooks, chased supervisors for signatures, and submitted hard copies. Lecturers had no real-time visibility into student progress.</p>
                        </div>
                    </div>
                    <div class="h-px bg-gray-100 w-full"></div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                            <i class="ph ph-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">The New Way (Digital)</h4>
                            <p class="text-gray-600 mt-2">A centralized dashboard where Students update daily journals, Companies approve them with a click, and the University monitors everything in real-time. Secure, fast, and eco-friendly.</p>
                        </div>
                    </div>
                </div>
                <div class="relative h-full min-h-[300px] rounded-2xl overflow-hidden shadow-lg group">
                    <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80" alt="Working on laptop" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8">
                        <p class="text-white font-medium">"Transforming how the University connects with the Industry."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">How the System Works</h2>
                <p class="text-gray-500 mt-4">A complete lifecycle management from registration to grading.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="group relative bg-gray-50 p-6 rounded-2xl hover:bg-blue-50 transition duration-300 border border-gray-100 hover:border-blue-100">
                    <div class="absolute -top-6 left-6 w-12 h-12 bg-white border-2 border-blue-600 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg shadow-sm">1</div>
                    <div class="mt-6">
                        <i class="ph ph-user-plus text-4xl text-gray-400 group-hover:text-blue-600 mb-4 transition"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Register & Apply</h3>
                        <p class="text-sm text-gray-600">Students create profiles and upload CVs. Companies post vacancies. Students apply directly through the portal.</p>
                    </div>
                </div>
                <div class="group relative bg-gray-50 p-6 rounded-2xl hover:bg-indigo-50 transition duration-300 border border-gray-100 hover:border-indigo-100">
                    <div class="absolute -top-6 left-6 w-12 h-12 bg-white border-2 border-indigo-600 text-indigo-600 rounded-full flex items-center justify-center font-bold text-lg shadow-sm">2</div>
                    <div class="mt-6">
                        <i class="ph ph-users-three text-4xl text-gray-400 group-hover:text-indigo-600 mb-4 transition"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Interview & Select</h3>
                        <p class="text-sm text-gray-600">Companies review applications, schedule interviews, and select the best candidates for the placements.</p>
                    </div>
                </div>
                <div class="group relative bg-gray-50 p-6 rounded-2xl hover:bg-purple-50 transition duration-300 border border-gray-100 hover:border-purple-100">
                    <div class="absolute -top-6 left-6 w-12 h-12 bg-white border-2 border-purple-600 text-purple-600 rounded-full flex items-center justify-center font-bold text-lg shadow-sm">3</div>
                    <div class="mt-6">
                        <i class="ph ph-notebook text-4xl text-gray-400 group-hover:text-purple-600 mb-4 transition"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Digital Logbooks</h3>
                        <p class="text-sm text-gray-600">During training, students fill daily diaries online. Supervisors review and approve them weekly—no paper needed.</p>
                    </div>
                </div>
                <div class="group relative bg-gray-50 p-6 rounded-2xl hover:bg-green-50 transition duration-300 border border-gray-100 hover:border-green-100">
                    <div class="absolute -top-6 left-6 w-12 h-12 bg-white border-2 border-green-600 text-green-600 rounded-full flex items-center justify-center font-bold text-lg shadow-sm">4</div>
                    <div class="mt-6">
                        <i class="ph ph-exam text-4xl text-gray-400 group-hover:text-green-600 mb-4 transition"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Final Evaluation</h3>
                        <p class="text-sm text-gray-600">At the end, the system generates performance reports, and lecturers grade the internship based on the digital data.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer" class="bg-gray-900 text-white pt-20 pb-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="logo.png" alt="InternSystem Logo" class="h-10 w-auto bg-white rounded p-1">
                        <div class="flex flex-col leading-tight">
                            <span class="text-xl font-bold text-white tracking-tight">InternSystem</span>
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">University of Vavuniya</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Bridging the gap between academia and industry. Our platform streamlines the internship process for students, companies, and faculty.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition duration-300">
                            <i class="ph ph-facebook-logo text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-400 hover:text-white transition duration-300">
                            <i class="ph ph-twitter-logo text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-700 hover:text-white transition duration-300">
                            <i class="ph ph-linkedin-logo text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition duration-300">
                            <i class="ph ph-instagram-logo text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6">Quick Links</h3>
                    <ul class="space-y-4">
                        <li><a href="index.php" class="text-gray-400 hover:text-blue-500 transition flex items-center gap-2"><i class="ph ph-caret-right text-xs"></i> Home</a></li>
                        <li><a href="login.php" class="text-gray-400 hover:text-blue-500 transition flex items-center gap-2"><i class="ph ph-caret-right text-xs"></i> Student Login</a></li>
                        <li><a href="register.php" class="text-gray-400 hover:text-blue-500 transition flex items-center gap-2"><i class="ph ph-caret-right text-xs"></i> Register Company</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-500 transition flex items-center gap-2"><i class="ph ph-caret-right text-xs"></i> Features</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6">Resources</h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-400 hover:text-blue-500 transition">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-500 transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-500 transition">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-500 transition">University Website</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6">Contact Us</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-gray-400">
                            <i class="ph ph-map-pin text-blue-500 text-xl mt-1"></i>
                            <span>Pampaimadu,<br>Vavuniya, Sri Lanka</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="ph ph-envelope text-blue-500 text-xl"></i>
                            <a href="mailto:info@vau.ac.lk" class="hover:text-blue-500 transition">info@vau.ac.lk</a>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="ph ph-phone text-blue-500 text-xl"></i>
                            <a href="tel:+94242223333" class="hover:text-blue-500 transition">+94 24 222 3333</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <div>
                    &copy; 2026 Student Internship Management System. All rights reserved.
                </div>
                <div class="flex items-center gap-1">
                    Designed & Developed by <span class="text-white font-semibold">Group 30 Project Team</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>