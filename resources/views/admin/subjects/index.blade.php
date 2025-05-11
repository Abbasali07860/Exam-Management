@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl shadow-2xl max-w-7xl border border-gray-200">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-12">
        <h2 class="text-4xl font-extrabold bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent tracking-tight">
            Subject Management
        </h2>
        <div class="flex gap-6">
            <a href="{{ route('admin.subjects.create') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-full text-base font-bold shadow-lg hover:shadow-glow hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Subject
            </a>
            <a href="{{ route('admin.subjects.assign') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-8 py-3 rounded-full text-base font-bold shadow-lg hover:shadow-glow hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                </svg>
                Assign Subjects
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.subjects.index') }}" class="mb-12 bg-white p-6 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label for="classroom_filter" class="block text-sm font-semibold text-gray-700 mb-2">Filter by Class</label>
                <div class="relative">
                    <select name="classroom_filter" id="classroom_filter"
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 appearance-none bg-white">
                        <option value="">All Classes</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ $classroomFilter == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <label for="teacher_filter" class="block text-sm font-semibold text-gray-700 mb-2">Filter by Teacher</label>
                <div class="relative">
                    <select name="teacher_filter" id="teacher_filter"
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 appearance-none bg-white">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $teacherFilter == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-full text-base font-bold shadow-lg hover:shadow-glow hover:scale-105 transition-all duration-300 w-full sm:w-auto">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Subjects Table -->
    <div class="overflow-x-auto rounded-2xl bg-white shadow-2xl border border-gray-200 animate-fade-in">
        <table class="min-w-full divide-y divide-gray-200 text-base">
            <thead class="bg-gradient-to-r from-blue-700 to-indigo-700 text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-8 py-5 text-left font-bold">Name</th>
                    <th class="px-8 py-5 text-left font-bold">Assigned Classes</th>
                    <th class="px-8 py-5 text-left font-bold">Assigned Teachers</th>
                    <th class="px-8 py-5 text-left font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($subjects->isEmpty())
                    <tr>
                        <td colspan="4" class="px-8 py-6 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-12">
                                <svg class="w-16 h-16 mb-4 text-gray-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xl font-semibold text-gray-600">No Subjects Found</span>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($subjects as $subject)
                        <tr class="even:bg-gray-50 hover:bg-blue-50 hover:shadow-md transition-all duration-300 transform hover:scale-[1.01]">
                            <td class="px-8 py-5 font-semibold text-gray-900">{{ $subject->name }}</td>
                            <td class="px-8 py-5 text-gray-700">
                                {{ $subject->classrooms->pluck('name')->implode(', ') ?: 'None' }}
                            </td>
                            <td class="px-8 py-5 text-gray-700">
                                {{ $subject->teachers->pluck('name')->implode(', ') ?: 'None' }}
                            </td>
                            <td class="px-8 py-5 flex space-x-4">
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                   class="flex items-center gap-2 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-6 py-2 rounded-full font-semibold hover:from-gray-300 hover:to-gray-400 hover:shadow-glow hover:scale-105 transition-all duration-300">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-full font-semibold hover:from-red-600 hover:to-red-700 hover:shadow-glow hover:scale-105 transition-all duration-300"
                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0H7a2 2 0 00-2 2v1h14V5a2 2 0 00-2-2h-3m-1 4v12"/>
                                        </svg>
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

    <!-- Pagination -->
    @if(!$subjects->isEmpty())
        <div class="mt-12 flex justify-center">
            {{ $subjects->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection