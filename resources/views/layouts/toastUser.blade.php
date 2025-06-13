@php
use Illuminate\Support\Facades\Session;
@endphp
<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .toastr {
        transition: all 0.5s ease-in-out;
    }
    .animate-slide-in-right {
        animation: slideInRight 0.5s ease-in-out forwards;
    }
    .animate-slide-out-right {
        animation: slideOutRight 0.5s ease-in-out forwards;
    }
    .toastr i {
        font-size: 1.25rem; /* Ensure icon size consistency */
    }
</style>
<div class="fixed top-4 right-4 z-50 space-y-2">
    @if (Session::has('success'))
        <div class="toastr toastr-success flex items-center bg-green-600 text-white p-4 rounded-lg shadow-lg max-w-sm" data-type="success">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="flex-1">{{ Session::get('success') }}</span>
            <button class="ml-2 focus:outline-none" onclick="dismissToastr(this)">
                <i class="fas fa-times text-white hover:text-gray-200"></i>
            </button>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="toastr toastr-error flex items-center bg-red-600 text-white p-4 rounded-lg shadow-lg max-w-sm" data-type="error">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span class="flex-1">{{ Session::get('error') }}</span>
            <button class="ml-2 focus:outline-none" onclick="dismissToastr(this)">
                <i class="fas fa-times text-white hover:text-gray-200"></i>
            </button>
        </div>
    @endif
    @if (Session::has('warning'))
        <div class="toastr toastr-warning flex items-center bg-yellow-600 text-white p-4 rounded-lg shadow-lg max-w-sm" data-type="warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="flex-1">{{ Session::get('warning') }}</span>
            <button class="ml-2 focus:outline-none" onclick="dismissToastr(this)">
                <i class="fas fa-times text-white hover:text-gray-200"></i>
            </button>
        </div>
    @endif
    @if (Session::has('info'))
        <div class="toastr toastr-info flex items-center bg-blue-600 text-white p-4 rounded-lg shadow-lg max-w-sm" data-type="info">
            <i class="fas fa-info-circle mr-2"></i>
            <span class="flex-1">{{ Session::get('info') }}</span>
            <button class="ml-2 focus:outline-none" onclick="dismissToastr(this)">
                <i class="fas fa-times text-white hover:text-gray-200"></i>
            </button>
        </div>
    @endif
</div>

<script>
    function dismissToastr(button) {
        const toastr = button.parentElement;
        toastr.classList.add('animate-slide-out-right');
        setTimeout(() => toastr.remove(), 500);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const toastrs = document.querySelectorAll('.toastr'); 
        toastrs.forEach((toastr, index) => {
            setTimeout(() => {
                toastr.classList.add('animate-slide-in-right');
            }, index * 200);

            setTimeout(() => {
                toastr.classList.add('animate-slide-out-right');
                setTimeout(() => toastr.remove(), 500);
            }, 5000);
        });
    });
</script>