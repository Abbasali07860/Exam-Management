@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-10 bg-gray-100 rounded-xl shadow-2xl max-w-7xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Add New User
            </a>
            <a href="#" id="open-status-modal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Change Status
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" id="filter-form"
          class="mb-6 bg-white px-6 py-4 rounded-xl shadow flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
        <div class="relative w-full md:w-1/3">
            <input type="text" name="search" placeholder="Search by name, email, or mobile"
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   oninput="document.getElementById('filter-form').submit();">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                 width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="w-full md:w-1/4">
            <select name="status"
                    onchange="document.getElementById('filter-form').submit();"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Filter by Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="w-full md:w-1/4">
            <select name="role"
                    onchange="document.getElementById('filter-form').submit();"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Filter by Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
            </select>
        </div>

        @if(request('search') || request('status') || request('role'))
        <div class="md:ml-auto">
            <a href="{{ route('admin.users.index') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-lg shadow-md hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 animate-pulse">
                Reset Filters
            </a>
        </div>
        @endif
    </form>

    <form id="user-table-form" method="POST">
        @csrf
        <div class="overflow-x-auto rounded-xl bg-white shadow-2xl">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-indigo-600 text-white uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="select-all" class="focus:ring-indigo-500 rounded">
                        </th>
                        <th class="px-6 py-4 text-left">Image</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Mobile</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($users->isEmpty())
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg class="w-12 h-12 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-lg font-medium">No Record Found</span>
                            </div>
                        </td>
                    </tr>
                    @else
                    @foreach($users as $user)
                    <tr class="hover:bg-indigo-50 transition duration-200 ease-in-out transform hover:scale-[1.01]">
                        <td class="px-6 py-3">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                   class="user-checkbox rounded focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-3">
                            <img src="{{ $user->image ? asset('storage/profile_images/' . $user->image) : asset('default/user.png') }}"
                                 alt="Profile Image" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200">
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $user->mobile }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ ucfirst($user->role) }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->status === 'active' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 space-x-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="bg-gray-200 text-gray-800 px-4 py-1 rounded-lg hover:bg-gray-300 transition-all duration-200 transform hover:scale-105">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-1 rounded-lg hover:bg-red-700 transition-all duration-200 transform hover:scale-105"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if(!$users->isEmpty())
        <div class="mt-6 flex justify-center">
            {{ $users->links() }}
        </div>
        @endif
    </form>

    <div id="status-modal" class="fixed inset-0 hidden bg-black bg-opacity-60 z-50 flex justify-center items-center transition-opacity duration-300">
        <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-2xl relative mx-4 transform transition-all duration-300 scale-95">
            <h3 class="text-2xl font-bold mb-6 text-gray-900">Change User Status</h3>
            <form id="bulk-status-form" method="POST" action="{{ route('admin.users.bulk-update-status') }}">
                @csrf
                <input type="hidden" name="user_ids" id="selected-user-ids">
                <label class="block mb-2 text-sm font-medium text-gray-700">Select Status</label>
                <select name="status"
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg mb-6 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="">-- Select Status --</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Deactivate</option>
                </select>
                <div class="flex justify-end space-x-3">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 transform hover:scale-105">
                        Update
                    </button>
                    <button type="button" id="close-status-modal"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 transform hover:scale-105">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const elements = {
            openBtn: document.getElementById('open-status-modal'),
            modal: document.getElementById('status-modal'),
            closeBtn: document.getElementById('close-status-modal'),
            userIdInput: document.getElementById('selected-user-ids'),
            checkboxes: document.querySelectorAll('.user-checkbox'),
            selectAll: document.getElementById('select-all'),
            form: document.getElementById('bulk-status-form')
        };

        const debounce = (fn, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        };

        const toggleModal = (show) => {
            elements.modal.classList.toggle('hidden', !show);
            elements.modal.classList.toggle('flex', show);
            elements.modal.querySelector('div').classList.toggle('scale-95', !show);
            elements.modal.querySelector('div').classList.toggle('scale-100', show);
        };

        const getSelectedIds = () =>
            Array.from(elements.checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

        const handlers = {
            openModal: () => {
                const selected = getSelectedIds();
                if (!selected.length) {
                    alert('Please select at least one user.');
                    return;
                }
                elements.userIdInput.value = JSON.stringify(selected);
                toggleModal(true);
            },
            closeModal: () => toggleModal(false),
            toggleSelectAll: function () {
                elements.checkboxes.forEach(cb => cb.checked = this.checked);
            },
            validateForm: function (e) {
                if (!this.querySelector('select[name="status"]').value) {
                    alert('Please select a status.');
                    e.preventDefault();
                }
            }
        };

        elements.openBtn.addEventListener('click', handlers.openModal);
        elements.closeBtn.addEventListener('click', handlers.closeModal);
        elements.selectAll.addEventListener('change', handlers.toggleSelectAll);
        elements.form.addEventListener('submit', handlers.validateForm);

        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.classList.add('bg-indigo-50', 'scale-[1.01]');
            });
            row.addEventListener('mouseleave', () => {
                row.classList.remove('bg-indigo-50', 'scale-[1.01]');
            });
        });
    })();
    </script>
</div>
@endsection