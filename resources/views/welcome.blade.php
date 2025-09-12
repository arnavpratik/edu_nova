<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNova | Smart Education Platform</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 10v6M2 10l10-5 10 5-10 5z'/><path d='M6 12v5c0 1.66 4 3 10 3s10-1.34 10-3v-5'/></svg>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c0 1.66 4 3 10 3s10-1.34 10-3v-5"></path>
                </svg>
                <span class="text-xl font-bold text-gray-800">EduNova</span>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition duration-300">
                    Register
                </a>
            </div>
        </div>
    </nav>
    
    <main class="flex-grow">
        <header class="bg-blue-50 py-20 px-4 md:py-32">
            <div class="container mx-auto text-center">
                <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4 animate-fade-in">
                    Your Path to Smarter Education.
                </h1>
                <p class="text-lg md:text-xl text-gray-700 max-w-2xl mx-auto mb-8 animate-fade-in delay-150">
                    EduNova is an interactive platform designed to help you master any subject with personalized lessons, quizzes, and real-time progress tracking.
                </p>
                <a href="{{ route('register') }}" class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-blue-700 transform hover:scale-105 transition duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300 animate-bounce-in delay-300">
                    Sign Up Now
                </a>
            </div>
        </header>

        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-12">Key Features</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-300">
                        <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 text-blue-600 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v2.5a2.5 2.5 0 0 1-2.5 2.5H6.5A2.5 2.5 0 0 1 4 19.5z"></path><path d="M20 17V5c0-1.1-.9-2-2-2H6.5A2.5 2.5 0 0 0 4 5.5v12.5"></path><path d="M10 8h6"></path><path d="M10 12h6"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Interactive Lessons & Quizzes</h3>
                        <p class="text-gray-600">Engage with dynamic content and test your knowledge with interactive quizzes for better retention.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-300">
                        <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 text-blue-600 rounded-full mb-4">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><path d="M9 18h6"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Personalized Revision Plans</h3>
                        <p class="text-gray-600">Our smart algorithm analyzes your quiz performance to create a unique revision plan just for you.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-300">
                        <div class="flex items-center justify-center h-16 w-16 mx-auto bg-blue-100 text-blue-600 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M12 16h9"></path><path d="M12 12h9"></path><path d="M21 8h-9"></path><path d="M3 12a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h18a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H3z"></path><path d="M7 6h6"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Comprehensive Dashboards</h3>
                        <p class="text-gray-600">Students and teachers get a clear view of progress, analytics, and weak topics through intuitive dashboards.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-blue-600 py-16 text-white text-center">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Transform Your Learning?</h2>
                <p class="text-lg mb-8">Join thousands of students and educators who are already using EduNova to achieve their goals.</p>
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-full shadow-lg hover:bg-gray-100 transform hover:scale-105 transition duration-300 ease-in-out">
                    Get Started Today
                </a>
            </div>
        </section>
    </main>


    <footer class="bg-gray-800 text-gray-300 py-4 px-4">
        <div class="mx-auto text-center max-w-2xl">
            <div class="flex justify-center space-x-6 md:space-x-8 mb-4">
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">About Us</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Contact</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Terms of Service</a>
            </div>
            <p class="text-sm text-gray-500">&copy; 2025 EduNova. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>