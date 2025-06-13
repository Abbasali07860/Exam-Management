@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Exam Management')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Orbitron -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(2px, -2px); }
            60% { transform: translate(-2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-in-out;
        }
        .animate-pulse-icon {
            animation: pulse 2s infinite ease-in-out;
        }
        .animate-glitch {
            animation: glitch 0.3s linear;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover i {
            transform: scale(1.1);
        }
        .header-shadow {
            box-shadow: 0 4px 12px rgba(0, 247, 255, 0.2);
        }
        .notification-bell {
            position: relative;
        }
        .notification-bell .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #FF2D55;
            color: #ffffff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            border: 2px solid #00F7FF;
            transition: transform 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 45, 85, 0.5);
        }
        .notification-bell:hover .badge {
            transform: scale(1.1);
        }
        .notification-item:hover {
            background-color: rgba(209, 213, 219, 0.5);
        }
        .notification-item.read {
            background-color: rgba(209, 213, 219, 0.7);
            opacity: 0.7;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #E2E8F0 0%, #D1D5DB 80%, rgba(192, 132, 252, 0.1) 100%);
            background-attachment: fixed;
        }
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        button:focus, a:focus {
            outline: 2px solid #00F7FF;
            outline-offset: 2px;
            box-shadow: 0 0 10px rgba(0, 247, 255, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans">
    <!-- Toastr Notifications -->
    @include('layouts.toastUser')

    <!-- Header -->
    @include('layouts.user.partials.header')

    <!-- Main Layout -->
    <div class="flex flex-1 mt-16">
        <!-- Sidebar (Visible After Login for Students) -->
        @auth
            @if (Auth::user()->role === 'student')
                @include('layouts.user.partials.sidebar')
            @endif
        @endauth

        <!-- Main Content -->
        <main class="flex-1 @auth @if (Auth::user()->role === 'student') md:ml-64 @endif @endauth p-6 md:p-10 bg-white rounded-tl-3xl shadow-inner border border-cyan-400/30">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    @include('layouts.user.partials.footer')

    <!-- JavaScript for Dropdown Toggle and Notifications -->
    <script>
        // CSRF Token
        let csrfToken;

        // User Dropdown
        window.toggleUserDropdown = function () {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        };

        // Notification Dropdown
        window.toggleNotificationDropdown = function () {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('hidden');
        };

        // Mark Notification as Read
        window.markAsRead = async function (notificationId) {
            try {
                const response = await fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const item = document.querySelector(`button[onclick="markAsRead('${notificationId}')"]`).closest('.notification-item');
                    item.classList.add('read');
                    item.querySelector('button').remove();
                    updateBadge();
                } else {
                    throw new Error(data.message || 'Failed to mark notification as read.');
                }
            } catch (error) {
                toastr.error(error.message || 'An error occurred while marking the notification as read.');
                console.error('Error marking notification as read:', error);
            }
        };

        // Clear All Notifications
        window.clearNotifications = async function () {
            try {
                const response = await fetch('/notifications/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const dropdown = document.getElementById('notification-dropdown');
                    dropdown.querySelector('.max-h-80').innerHTML = '<div class="p-4 text-center text-gray-500"><p>No new notifications.</p></div>';
                    dropdown.querySelector('button[onclick="clearNotifications()"]').remove();
                    updateBadge();
                } else {
                    throw new Error(data.message || 'Failed to clear notifications.');
                }
            } catch (error) {
                toastr.error(error.message || 'An error occurred while clearing notifications.');
                console.error('Error clearing notifications:', error);
            }
        };

        // Update Notification Badge
        function updateBadge() {
            const badge = document.querySelector('.notification-bell .badge');
            const unreadItems = document.querySelectorAll('.notification-item:not(.read)').length;
            if (badge) {
                if (unreadItems > 0) {
                    badge.textContent = unreadItems;
                } else {
                    badge.remove();
                }
            }
        }

        // Initialize on DOM Load
        document.addEventListener('DOMContentLoaded', () => {
            // Retrieve CSRF Token
            csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error('CSRF token not found');
                toastr.error('CSRF token missing. Please refresh the page.');
                return;
            }

            // Close Dropdowns on Outside Click
            document.addEventListener('click', function (event) {
                // User Dropdown
                const userDropdown = document.getElementById('user-dropdown');
                const userButton = event.target.closest('button[onclick="toggleUserDropdown()"]');
                if (userDropdown && !userButton && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }

                // Notification Dropdown
                const notificationDropdown = document.getElementById('notification-dropdown');
                const notificationButton = event.target.closest('button[onclick="toggleNotificationDropdown()"]');
                if (notificationDropdown && !notificationButton && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>