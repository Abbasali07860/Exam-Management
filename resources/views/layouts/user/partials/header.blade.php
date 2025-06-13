@php
    use Illuminate\Support\Facades\Auth;
    $unreadNotifications = Auth::check() ? Auth::user()->unreadNotifications : collect([]);
    $unreadCount = $unreadNotifications->count();
@endphp

<header class="bg-gray-200 py-4 px-6 fixed top-0 w-full z-20 border-b border-cyan-400/50 header-shadow">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3 animate-fade-in">
            <i class="fas fa-graduation-cap text-3xl text-amber-500"></i>
            <h1 class="text-xl font-extrabold text-gray-800 tracking-tight font-orbitron">
                Exam Management Portal
            </h1>
        </div>
        <!-- User Dropdown and Notifications -->
        @auth
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <div class="relative">
                    <button class="notification-bell focus:outline-none" onclick="toggleNotificationDropdown()" aria-label="Notifications">
                        <i class="fas fa-bell text-lg text-gray-800 hover:text-amber-500 transition-colors duration-200 animate-pulse-icon"></i>
                        @if($unreadCount > 0)
                            <span class="badge">{{ $unreadCount }}</span>
                        @endif
                    </button>
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white/10 backdrop-blur-lg rounded-xl shadow-2xl border border-cyan-400/50 z-30 animate-fade-in">
                        <div class="flex items-center justify-between p-4 border-b border-gray-400">
                            <h3 class="text-lg font-semibold text-gray-800 font-orbitron">Notifications</h3>
                            @if($unreadCount > 0)
                                <button onclick="clearNotifications()" class="text-sm text-gray-600 hover:text-amber-500 font-medium transition-colors duration-200">Clear All</button>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($unreadNotifications as $notification)
                                <div class="notification-item {{ $notification->read_at ? 'read' : '' }} p-4 border-b border-gray-400 hover:bg-gray-100/50 transition-colors duration-200">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-info-circle text-green-500"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$notification->read_at)
                                            <button onclick="markAsRead('{{ $notification->id }}')" class="text-gray-600 hover:text-amber-500 text-sm font-medium transition-colors duration-200">Mark as Read</button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-600">
                                    <p class="text-sm">No new notifications.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- User Dropdown -->
                <div class="relative">
                    <button class="flex items-center gap-2 focus:outline-none" onclick="toggleUserDropdown()" aria-label="User Menu">
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-800 font-bold shadow-[0_0_10px_rgba(0,247,255,0.5)] hover:shadow-[0_0_15px_rgba(0,247,255,0.7)] transition-shadow duration-200">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-800 font-semibold hover:text-amber-500 transition-colors duration-200 text-base">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-800"></i>
                    </button>
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white/10 backdrop-blur-lg rounded-xl shadow-2xl border border-cyan-400/50 z-30 animate-fade-in">
                        <a href="{{ route('student.profile') }}" class="flex items-center gap-2 px-4 py-2 text-gray-800 hover:bg-gray-100/50 hover:text-amber-500 transition-colors duration-200">
                            <i class="fas fa-user text-green-500"></i>
                            <span class="text-sm">Profile</span>
                        </a>
                        <form action="{{ route('user.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100/50 hover:text-amber-500 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt text-green-500"></i>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</header>