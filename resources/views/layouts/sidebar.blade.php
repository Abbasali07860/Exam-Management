<body>
<!-- Sidebar -->
<div class="sidebar">
    <nav>
        <ul>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.exams.index') }}" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Exam Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.subjects.index') }}" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span>Subject Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.classrooms.index') }}" class="menu-item">
                    <i class="fas fa-chalkboard"></i>
                    <span>Class Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.questions.index') }}" class="menu-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Question Bank</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}
</script>
</body>