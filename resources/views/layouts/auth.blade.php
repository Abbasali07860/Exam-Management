<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow-md py-4 px-6 flex items-center justify-center">
        <div class="flex items-center gap-3">
            <i class="fas fa-book-open text-3xl text-indigo-600 animate-pulse"></i>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-coral-500 bg-clip-text text-transparent">
                Exam Management
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner py-4 text-center text-gray-600">
        <p>&copy; {{ date('Y') }} Exam Management. All rights reserved.</p>
    </footer>

    <!-- JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr messages (load AFTER toastr.js is loaded) -->
    @include('layouts.toastr')

</body>
</html>
