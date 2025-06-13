@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 py-10 px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-10">
            <h2 class="flex items-center gap-3 text-3xl font-bold text-gray-800">
                <i class="fas fa-chart-bar text-indigo-600 animate-pulse"></i>
                Result Management
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('admin.results.export', 'csv') }}"
                   class="group inline-flex items-center gap-2 bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:bg-green-700 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-file-csv group-hover:animate-bounce"></i> Export CSV
                </a>
                <a href="{{ route('admin.results.export', 'pdf') }}"
                   class="group inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:bg-blue-700 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-file-pdf group-hover:animate-bounce"></i> Export PDF
                </a>
            </div>
        </div>

        <!-- Results Table -->
        <div class="overflow-x-auto rounded-xl bg-white shadow-xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-700 text-white uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Exam Name</th>
                        <th class="px-6 py-4 text-left font-semibold">Student Name</th>
                        <th class="px-6 py-4 text-left font-semibold">Score</th>
                        <th class="px-6 py-4 text-left font-semibold">Date Attempted</th>
                        <th class="px-6 py-4 text-left font-semibold">Published</th>
                        <th class="px-6 py-4 text-left font-semibold">Allow Answers</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($results->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-14 h-14 mb-3 text-gray-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-600">No Results Found</span>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($results as $result)
                            <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                <td class="px-6 py-5 text-gray-900 font-medium">{{ $result->exam->title }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $result->user->name }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $result->score }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $result->end_time->format('Y-m-d') }}</td>
                                <td class="px-6 py-5">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               class="sr-only peer toggle-publish"
                                               data-result-id="{{ $result->id }}"
                                               {{ $result->published ? 'checked' : '' }}>
                                        <div class="w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-indigo-600 transition-colors duration-300 shadow-sm"></div>
                                        <div class="absolute left-1 top-0.5 w-5 h-5 bg-white rounded-full border border-gray-300 peer-checked:translate-x-6 transition-transform duration-300 shadow-sm"></div>
                                    </label>
                                </td>
                                <td class="px-6 py-5">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               class="sr-only peer toggle-allow"
                                               data-result-id="{{ $result->id }}"
                                               {{ $result->allow_view_answers ? 'checked' : '' }}>
                                        <div class="w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-indigo-600 transition-colors duration-300 shadow-sm"></div>
                                        <div class="absolute left-1 top-0.5 w-5 h-5 bg-white rounded-full border border-gray-300 peer-checked:translate-x-6 transition-transform duration-300 shadow-sm"></div>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if(!$results->isEmpty())
            <div class="mt-8 flex justify-center">
                {{ $results->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- JavaScript for Toggle Button -->
   <script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            console.error('CSRF token not found');
            toastr.error('CSRF token missing.');
            return;
        }

        // Publish toggle
        document.querySelectorAll('.toggle-publish').forEach(toggle => {
            toggle.addEventListener('change', async function () {
                const resultId = this.dataset.resultId;
                const isPublished = this.checked;

                try {
                    const response = await fetch(`{{ route("admin.results.publish", ":id") }}`.replace(':id', resultId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ published: isPublished })
                    });

                    const data = await response.json();
                    if (!data.success) {
                        this.checked = !isPublished;
                        throw new Error(data.message || 'Failed to update status.');
                    }

                    toastr.success(data.message);
                } catch (error) {
                    this.checked = !isPublished;
                    toastr.error(error.message || 'An error occurred.');
                    console.error(error);
                }
            });
        });

        // Allow View Answers toggle
        document.querySelectorAll('.toggle-allow').forEach(toggle => {
            toggle.addEventListener('change', async function () {
                const resultId = this.dataset.resultId;
                const isAllowed = this.checked;

                try {
                    const response = await fetch(`{{ route("admin.results.allow_answers", ":id") }}`.replace(':id', resultId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ allow_view_answers: isAllowed }) 
                    });

                    const data = await response.json();
                    if (!data.success) {
                        this.checked = !isAllowed;
                        throw new Error(data.message || 'Failed to update view status.');
                    }
                    toastr.success(data.message);
                } catch (error) {
                    this.checked = !isAllowed;
                    toastr.error(error.message || 'An error occurred.');
                    console.error(error);
                }
            });
        });
    });
</script>



    <!-- Add CSS to ensure peer-checked styles take precedence -->
    <style>
        .peer:checked ~ .peer-checked\:bg-indigo-600 {
            background-color: #4f46e5 !important;
        }
        .peer:checked ~ .peer-checked\:translate-x-6 {
            transform: translateX(1.5rem) !important;
        }
    </style>
@endsection