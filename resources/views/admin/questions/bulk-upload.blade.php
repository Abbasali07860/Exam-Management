@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl shadow-2xl max-w-8xl">
    <h2 class="flex items-center gap-2 text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-green-600 bg-clip-text text-transparent">
        Bulk Upload Questions
    </h2>

    <div class="space-y-8">
        <!-- Instructions -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-200">
            <h3 class="text-xl font-semibold text-slate-800 mb-4 border-b-2 border-teal-500 pb-2">Instructions</h3>
            <ul class="list-disc list-inside text-slate-700 space-y-3">
                <li>Download the template to prepare your questions.</li>
                <li>Supported question types: <code>mcq</code>, <code>true_false</code>, <code>descriptive</code>.</li>
                <li>For MCQ: Provide four options (option_a to option_d) and correct_answer as 0-3 (index of correct option).</li>
                <li>For True/False: Correct_answer must be <code>true</code> or <code>false</code>.</li>
                <li>For Descriptive: Options and correct_answer can be empty.</li>
                <li>Subject_id is optional; leave blank or use a valid subject ID.</li>
                <li>Marks must be a positive integer (default: 1).</li>
                <li>File must be in CSV or XLSX format.</li>
            </ul>
            <a href="{{ route('admin.questions.download-template') }}"
               class="mt-6 inline-block bg-gradient-to-r from-teal-600 to-teal-700 hover:from-amber-500 hover:to-amber-600 text-black px-6 py-3 rounded-xl text-base font-bold border border-teal-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Download Template
            </a>
        </div>

        <!-- Upload Form -->
        <div class="bg-slate-50 p-8 rounded-2xl shadow-lg border border-slate-200">
            <form method="POST" action="{{ route('admin.questions.bulk-store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="file" class="block text-sm font-semibold text-slate-800 mb-2">Upload File</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-teal-500 transition-all duration-300">
                        <input type="file" name="file" id="file" accept=".csv,.xlsx"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <p class="text-slate-700">Drag and drop your file here, or <span class="text-teal-600 font-semibold">browse</span></p>
                        <p class="text-sm text-slate-500 mt-1">Supported formats: CSV, XLSX</p>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg border border-red-200">{{ $message }}</p>
                    @enderror
                </div>

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
</div>
@endsection