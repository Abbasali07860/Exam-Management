
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl shadow-2xl max-w-8xl">
    <h2 class="text-4xl font-black bg-gradient-to-r from-teal-600 to-teal-800 bg-clip-text text-black tracking-tight mb-12">
        Bulk Upload Questions
    </h2>

    <div class="bg-slate-50 p-8 rounded-2xl shadow-lg border border-slate-200">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Instructions -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-slate-800 mb-2">Instructions</h3>
            <ul class="list-disc list-inside text-slate-600">
                <li>Download the template to get the correct format for uploading questions.</li>
                <li>The <strong>exam_id</strong> column must correspond to a valid exam ID. Available exams:</li>
                <ul class="list-circle list-inside ml-4">
                    @foreach($exams as $exam)
                        <li>{{ $exam->title }} (ID: {{ $exam->id }})</li>
                    @endforeach
                </ul>
                <li>The <strong>subject_id</strong> column must correspond to a valid subject ID. Available subjects:</li>
                <ul class="list-circle list-inside ml-4">
                    @foreach($subjects as $subject)
                        <li>{{ $subject->name }} (ID: {{ $subject->id }})</li>
                    @endforeach
                </ul>
                <li>For MCQ questions, include all four options (option_a to option_d) and set correct_answer to the option's index: 0 (option_a), 1 (option_b), 2 (option_c), or 3 (option_d).</li>
                <li>For True/False questions, set correct_answer to 'true' or 'false'.</li>
                <li>For Descriptive questions, leave the options blank.</li>
            </ul>
        </div>

        <!-- Download Template -->
        <div class="mb-6">
            <a href="{{ route('admin.questions.download-template') }}"
               class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-6 py-3 rounded-xl text-base font-bold shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Download Template
            </a>
        </div>

        <!-- Upload Form -->
        <form method="POST" action="{{ route('admin.questions.bulk-store') }}" enctype="multipart/form-data" class="bg-slate-50 p-8 rounded-2xl shadow-lg border border-slate-200">
            @csrf

            <div class="mb-6">
                <label for="file" class="block text-sm font-semibold text-slate-800 mb-2">Upload Excel File</label>
                <input type="file" name="file" id="file"
                       class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700"
                       accept=".csv, .xlsx" required>
                @error('file')
                    <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.questions.index') }}"
                   class="bg-gradient-to-r from-slate-200 to-slate-300 text-slate-800 px-6 py-3 rounded-xl text-base font-bold border border-slate-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-amber-500 hover:to-amber-600 text-black px-6 py-3 rounded-xl text-base font-bold border border-teal-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                    Upload Questions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection