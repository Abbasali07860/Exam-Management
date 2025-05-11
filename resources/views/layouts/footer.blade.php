<!-- Footer -->
<footer class="bg-white border-t border-gray-200 text-center py-4 mt-auto">
    <p class="text-sm text-gray-500">
        &copy; {{ date('Y') }} Laravel App. All rights reserved.
    </p>
</footer>
<script>
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('toggle'));
            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>

