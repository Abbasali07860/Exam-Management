@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-8xl">
        <div class="flex justify-between items-center mb-8">
            <h2 class="flex items-center gap-2 text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-green-600 bg-clip-text text-transparent">
                <span class="text-indigo-600">📝</span>
                Exam Management
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('admin.exams.create') }}"
                   class="bg-gradient-to-r from-white-500 to-white-600 text-black px-6 py-3 rounded-xl text-sm font-semibold shadow-lg transition-transform transform hover:scale-105">
                    ➕ Add New Exam
                </a>
                <a href="#" id="open-status-modal"
                   class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg transition-transform transform hover:scale-105">
                    Change Status
                </a>
            </div>
        </div>

        <form id="exam-table-form" method="POST">
            @csrf
            <div class="overflow-x-auto rounded-2xl bg-white shadow-2xl border border-gray-200 animate-fade-in">
                <table class="min-w-full divide-y divide-gray-200 text-base">
                    <thead class="bg-gradient-to-r from-indigo-700 to-indigo-600 text-white uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="select-all" class="focus:ring-indigo-500 rounded">
                            </th>
                            <th class="px-6 py-4 text-left">Title</th>
                            <th class="px-6 py-4 text-left">Start Date</th>
                            <th class="px-6 py-4 text-left">End Date</th>
                            <th class="px-6 py-4 text-left">Duration</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Assigned Students</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($exams->isEmpty())
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-12">
                                        <svg class="w-16 h-16 mb-4 text-gray-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xl font-semibold text-gray-600">No Exams Found</span>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($exams as $exam)
                                <tr class="even:bg-gray-50 hover:bg-indigo-50 transition-all duration-300 transform hover:scale-[1.01]">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="exam_ids[]" value="{{ $exam->id }}"
                                               class="exam-checkbox rounded focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $exam->title }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $exam->formatted_start_date }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $exam->formatted_end_date }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $exam->duration }} min</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                                            {{ $exam->status === 'active' ? 'bg-gradient-to-r from-green-400 to-green-500 text-green-900' : 'bg-gradient-to-r from-red-400 to-red-500 text-red-900' }}">
                                            {{ ucfirst($exam->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $exam->students->pluck('name')->implode(', ') ?: 'None' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <!-- Existing Edit Button -->
                                            <a href="{{ route('admin.exams.edit', $exam->id) }}"
                                            class="flex items-center gap-2 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold 
                                            hover:bg-gray-300 transition-all duration-300 shadow-md">
                                                ✏️ <span>Edit</span>
                                            </a>

                                            <!-- New Instructions Button -->
                                            <a href="{{ route('admin.exams.instructions', $exam->id) }}"
                                            class="flex items-center gap-2 bg-indigo-200 text-indigo-800 px-4 py-2 rounded-lg font-semibold 
                                            hover:bg-indigo-300 transition-all duration-300 shadow-md">
                                                📜 <span>Instructions</span>
                                            </a>

                                            <!-- Existing Delete Button -->
                                            <form method="POST" action="{{ route('admin.exams.destroy', $exam->id) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg font-semibold 
                                                        hover:bg-red-700 transition-all duration-300 shadow-md"
                                                        onclick="return confirm('Are you sure you want to delete this exam?')">
                                                    🗑️ <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @if(!$exams->isEmpty())
                <div class="mt-8 flex justify-center">
                    {{ $exams->links() }}
                </div>
            @endif
        </form>

        <div id="status-modal" class="fixed inset-0 hidden bg-black bg-opacity-60 z-50 flex justify-center items-center transition-opacity duration-300">
            <div class="bg-gradient-to-br from-white to-gray-50 w-full max-w-md p-10 rounded-2xl shadow-2xl border border-gray-200 relative mx-4 transform transition-all duration-300 scale-90">
                <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Change Exam Status</h3>
                <form id="bulk-status-form" method="POST" action="{{ route('admin.exams.bulk-update-status') }}">
                    @csrf
                    <input type="hidden" name="exam_ids" id="selected-exam-ids">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Select Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl mb-6 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        <option value="">-- Select Status --</option>
                        <option value="active">Activate</option>
                        <option value="inactive">Deactivate</option>
                    </select>
                    <div class="flex justify-end space-x-4">
                        <button type="submit"
                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 hover:scale-105 transition-all duration-300">
                            Update
                        </button>
                        <button type="button" id="close-status-modal"
                                class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:from-gray-300 hover:to-gray-400 hover:scale-105 transition-all duration-300">
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
                examIdInput: document.getElementById('selected-exam-ids'),
                checkboxes: document.querySelectorAll('.exam-checkbox'),
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
                elements.modal.querySelector('div').classList.toggle('scale-90', !show);
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
                        alert('Please select at least one exam.');
                        return;
                    }
                    elements.examIdInput.value = JSON.stringify(selected);
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