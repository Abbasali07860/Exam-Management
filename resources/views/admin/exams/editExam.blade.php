@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl shadow-2xl max-w-8xl">
    <h2 class="text-4xl font-black bg-gradient-to-r from-teal-600 to-teal-800 bg-clip-text text-black tracking-tight mb-12">
        Edit Exam: {{ $exam->title }}
    </h2>

    <form method="POST" action="{{ route('admin.exams.update', $exam) }}" class="bg-slate-50 p-8 rounded-2xl shadow-lg border border-slate-200">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="lg:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-800 mb-2">Exam Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $exam->title) }}"
                       class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                @error('title')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="lg:col-span-2">
                <label for="description" class="block text-sm font-semibold text-slate-800 mb-2">Description (Optional)</label>
                <textarea name="description" id="description" class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700" rows="5">{{ old('description', $exam->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-semibold text-slate-800 mb-2">Start Date</label>
                <input type="datetime-local" name="start_date" id="start_date" 
                       value="{{ old('start_date', $exam->start_date ? $exam->start_date->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                @error('start_date')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-semibold text-slate-800 mb-2">End Date</label>
                <input type="datetime-local" name="end_date" id="end_date" 
                       value="{{ old('end_date', $exam->end_date ? $exam->end_date->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                @error('end_date')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration -->
            <div>
                <label for="duration" class="block text-sm font-semibold text-slate-800 mb-2">Duration (in minutes)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration', $exam->duration) }}"
                       class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700" min="1">
                @error('duration')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-slate-800 mb-2">Status</label>
                <select name="status" id="status" class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                    <option value="active" {{ old('status', $exam->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $exam->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assign Students -->
            <div class="lg:col-span-2">
                <label for="student_ids" class="block text-sm font-semibold text-slate-800 mb-2">Assign Students (Optional)</label>
                <select name="student_ids[]" id="student_ids" multiple class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                    @if(isset($students) && $students->isNotEmpty())
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $exam->students->contains($student->id) ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No students available</option>
                    @endif
                </select>
                <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple students</p>
                @error('student_ids')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('admin.exams.index') }}"
               class="bg-gradient-to-r from-slate-200 to-slate-300 text-slate-800 px-6 py-3 rounded-xl text-base font-bold border border-slate-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Cancel
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-amber-500 hover:to-amber-600 text-black px-6 py-3 rounded-xl text-base font-bold border border-teal-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Update Exam
            </button>
        </div>
    </form>
</div>
@endsection