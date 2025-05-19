@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-7xl">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-8">Add New Class</h2>

    <form method="POST" action="{{ route('admin.classrooms.store') }}" class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Subjects</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-64 overflow-y-auto p-4 border border-gray-200 rounded-xl">
                @if($subjects->isEmpty())
                    <p class="text-gray-500 text-sm">No subjects available. Please add subjects first.</p>
                @else
                    @foreach($subjects as $subject)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                   class="rounded focus:ring-blue-500" {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                        </label>
                    @endforeach
                @endif
            </div>
            @error('subject_ids')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.classrooms.index') }}"
               class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-6 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                Cancel
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                Add Class
            </button>
        </div>
    </form>
</div>
@endsection