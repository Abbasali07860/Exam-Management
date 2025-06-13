@php
use Illuminate\Support\Facades\Auth;
@endphp
<header class="site-header">
    <div class="container header-content">
        <!-- Left: Sidebar Toggle + App Title -->
        <div class="left-section">
            <div class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </div>
            <h2 class="app-title">Admin Panel</h2>
        </div>

        <!-- Center: Navigation Tabs (Only Dashboard) -->
        <nav class="navbar-tabs">
            <a href="#">Dashboard</a>
        </nav>

        <!-- Right: User Dropdown -->
        <div class="right-section">
            <div class="user-dropdown">
                <input type="checkbox" id="dropdown-toggle" hidden>
                <label for="dropdown-toggle" class="user-info">
                    <div class="profile-container">
                        <div class="profile-circle">
                            @if (Auth::check() && $user)
                                <img src="{{ $user->image ? asset('storage/profile_images/' . $user->image) : asset('default/user.png') }}"
                                     alt="User Image">
                            @else
                                <img src="{{ asset('default/user.png') }}" alt="Default User Image">
                            @endif
                        </div>
                    </div>
                    <span class="user-name">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </label>

                <ul class="dropdown-menu">
                    @if (Auth::check())
                        <li class="dropdown-header">
                            <strong>{{ Auth::user()->name }}</strong><br>
                            <small>{{ Auth::user()->email }}</small>
                        </li>
                        <li><a href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                        <li><a href="{{ route('admin.password.change') }}"><i class="fas fa-lock"></i> Change Password</a></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>