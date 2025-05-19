<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\Controller;
use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $subjectFilter = $request->query('subject_filter');

        $query = Question::with('subject');
        
        if ($subjectFilter) {
            $query->where('subject_id', $subjectFilter);
        }

        $questions = $query->paginate(5);
        $subjects = Subject::all();

        return view('admin.questions.index', compact('questions', 'subjects', 'subjectFilter'));
    }

    // Other methods (create, store, edit, update, destroy, bulkUpload, bulkStore, downloadTemplate) remain unchanged
    public function create(Question $question)
    {
        $subjects = Subject::all();
        return view('admin.questions.create', compact('subjects','question'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'type' => ['required', 'in:mcq,true_false,descriptive'],
            'question_text' => ['required', 'string'],
            'options' => ['required_if:type,mcq', 'array', 'size:4'],
            'options.*' => ['required_if:type,mcq', 'string'],
            'correct_answer' => ['required', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['type'] === 'mcq' && !in_array($validated['correct_answer'], ['0', '1', '2', '3'])) {
            return back()->withErrors(['correct_answer' => 'Correct answer must be between 0 and 3 for MCQ.']);
        }
        if ($validated['type'] === 'true_false' && !in_array($validated['correct_answer'], ['true', 'false'])) {
            return back()->withErrors(['correct_answer' => 'Correct answer must be "true" or "false" for True/False.']);
        }

        Question::create([
            'subject_id' => $validated['subject_id'],
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['type'] === 'mcq' ? $validated['options'] : null,
            'correct_answer' => $validated['correct_answer'],
            'marks' => $validated['marks'],
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $subjects = Subject::all();
        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'type' => ['required', 'in:mcq,true_false,descriptive'],
            'question_text' => ['required', 'string'],
            'options' => ['required_if:type,mcq', 'array', 'size:4'],
            'options.*' => ['required_if:type,mcq', 'string'],
            'correct_answer' => ['required', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['type'] === 'mcq' && !in_array($validated['correct_answer'], ['0', '1', '2', '3'])) {
            return back()->withErrors(['correct_answer' => 'Correct answer must be between 0 and 3 for MCQ.']);
        }
        if ($validated['type'] === 'true_false' && !in_array($validated['correct_answer'], ['true', 'false'])) {
            return back()->withErrors(['correct_answer' => 'Correct answer must be "true" or "false" for True/False.']);
        }

        $question->update([
            'subject_id' => $validated['subject_id'],
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['type'] === 'mcq' ? $validated['options'] : null,
            'correct_answer' => $validated['correct_answer'],
            'marks' => $validated['marks'],
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully.');
    }

    public function bulkUpload()
    {
        return view('admin.questions.bulk-upload');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx'],
        ]);

        Excel::import(new QuestionsImport, $request->file('file'));

        return redirect()->route('admin.questions.index')->with('success', 'Questions imported successfully.');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/questions_template.xlsx');
        if (!file_exists($filePath)) {
            $templateData = [
                ['subject_id', 'type', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'marks'],
                [1, 'mcq', 'What is 2 + 2?', '2', '4', '6', '8', '1', '2'],
                [1, 'true_false', 'The earth is flat.', '', '', '', '', 'false', '1'],
                [1, 'descriptive', 'Describe the water cycle.', '', '', '', '', '', '5'],
            ];
            Excel::store($templateData, 'public/templates/questions_template.xlsx');
        }

        return response()->download($filePath, 'questions_template.xlsx');
    }
}