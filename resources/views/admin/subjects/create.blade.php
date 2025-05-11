@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-7xl">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-8">Add New Subject</h2>

    <form method="POST" action="{{ route('admin.subjects.store') }}" class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Subject Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="classroom_id" class="block text-sm font-semibold text-gray-700 mb-2">Assign to Class (Optional)</label>
            <select name="classroom_id" id="classroom_id"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                <option value="">Select a Class</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
            @error('classroom_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="teacher_id" class="block text-sm font-semibold text-gray-700 mb-2">Assign to Teacher (Optional)</label>
            <select name="teacher_id" id="teacher_id"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                <option value="">Select a Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.subjects.index') }}"
               class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-6 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                Cancel
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                Add Subject
            </button>
        </div>
    </form>
</div>
@endsection