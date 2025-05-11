<header class="site-header">
    <div class="container header-content">
        
        <!-- Left: Sidebar Toggle + App Title -->
        <div class="left-section">
            <div class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </div>
            <h2 class="app-title">Admin Panel</h2>
        </div>

        <!-- Center: Navigation Tabs -->
        <nav class="navbar-tabs">
            <a href="#">Dashboard</a>
            <a href="#">Training</a>
            <a href="#">Skill</a>
            <a href="#">Internship</a>
            <a href="#">Hiring</a>
        </nav>

        <!-- Right: Notification + User Dropdown -->
        <div class="right-section">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
            </div>

            <div class="user-dropdown">
                <input type="checkbox" id="dropdown-toggle" hidden>
                <label for="dropdown-toggle" class="user-info">
                    <div class="profile-container">
                        <div class="profile-circle">
                            <img src="{{ $user->image ? asset('storage/profile_images/' . $user->image) : asset('default/user.png') }}"
                                 alt="User Image">
                        </div>
                        <span class="online-dot"></span>
                    </div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </label>

                <ul class="dropdown-menu">
                    <li class="dropdown-header">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small>{{ Auth::user()->email }}</small>
                    </li>
                    <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                    <li><a href="{{ route('password.change') }}"><i class="fas fa-lock"></i> Change Password</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</header>
