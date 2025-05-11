@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-7xl">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-8">Assign Subjects</h2>

    <form method="POST" action="{{ route('admin.subjects.storeAssignments') }}" class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        @csrf

        <div class="mb-6">
            <label for="subject_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Subject</label>
            <select name="subject_id" id="subject_id"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('subject_id') border-red-500 @enderror">
                <option value="">Select a Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Assign to Classes (Optional)</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-64 overflow-y-auto p-4 border border-gray-200 rounded-xl">
                @foreach($classrooms as $classroom)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="classroom_ids[]" value="{{ $classroom->id }}"
                               class="rounded focus:ring-blue-500" {{ in_array($classroom->id, old('classroom_ids', [])) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $classroom->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('classroom_ids')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Assign to Teachers (Optional)</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-64 overflow-y-auto p-4 border border-gray-200 rounded-xl">
                @foreach($teachers as $teacher)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}"
                               class="rounded focus:ring-blue-500" {{ in_array($teacher->id, old('teacher_ids', [])) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $teacher->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('teacher_ids')
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
                Save Assignments
            </button>
        </div>
    </form>
</div>
@endsection