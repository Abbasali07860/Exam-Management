<aside class="w-64 bg-gray-200 backdrop-blur-lg bg-opacity-90 fixed top-16 left-0 h-[calc(100vh-64px)] shadow-2xl z-10 animate-slide-in hidden md:block border-r border-cyan-400/30">
    <nav class="mt-10 px-4">
        <ul class="space-y-3">
            <li>
                <a href="{{ route('student.dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 text-base font-medium text-gray-800 rounded-lg hover:bg-gray-300/50 hover:shadow-[0_0_15px_rgba(0,247,255,0.3)] transition-all duration-300 {{ request()->routeIs('student.dashboard') ? 'bg-gray-300/70 shadow-[0_0_15px_rgba(0,247,255,0.5)]' : '' }}">
                    <i class="fas fa-tachometer-alt text-amber-500 hover:text-amber-400 transition-transform duration-300 hover:animate-glitch"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.exams') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 text-base font-medium text-gray-800 rounded-lg hover:bg-gray-300/50 hover:shadow-[0_0_15px_rgba(0,247,255,0.3)] transition-all duration-300 {{ request()->routeIs('student.exams') ? 'bg-gray-300/70 shadow-[0_0_15px_rgba(0,247,255,0.5)]' : '' }}">
                    <i class="fas fa-book-open text-amber-500 hover:text-amber-400 transition-transform duration-300 hover:animate-glitch"></i>
                    <span>Exams</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.results') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 text-base font-medium text-gray-800 rounded-lg hover:bg-gray-300/50 hover:shadow-[0_0_15px_rgba(0,247,255,0.3)] transition-all duration-300 {{ request()->routeIs('student.results') ? 'bg-gray-300/70 shadow-[0_0_15px_rgba(0,247,255,0.5)]' : '' }}">
                    <i class="fas fa-chart-line text-amber-500 hover:text-amber-400 transition-transform duration-300 hover:animate-glitch"></i>
                    <span>Results</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.profile') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 text-base font-medium text-gray-800 rounded-lg hover:bg-gray-300/50 hover:shadow-[0_0_15px_rgba(0,247,255,0.3)] transition-all duration-300 {{ request()->routeIs('student.profile') ? 'bg-gray-300/70 shadow-[0_0_15px_rgba(0,247,255,0.5)]' : '' }}">
                    <i class="fas fa-user-graduate text-amber-500 hover:text-amber-400 transition-transform duration-300 hover:animate-glitch"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>