<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['students', 'subject']);

        if ($search = $request->search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($role = $request->role) {
            $query->whereHas('users', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        $exams = $query->paginate(10)->appends($request->query());
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['teacher', 'student'])->get();
        $subjects = Subject::all();
        return view('admin.exams.createExam', compact('users', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date|after:start_date|date_format:Y-m-d\TH:i',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'subject_id' => 'required|exists:subjects,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'instructions' => 'nullable|string',
            'instructions_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Convert start_date and end_date from IST to UTC
        $startDateIST = Carbon::parse($data['start_date'], 'Asia/Kolkata');
        $endDateIST = Carbon::parse($data['end_date'], 'Asia/Kolkata');

        $examData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $startDateIST->setTimezone('UTC'),
            'end_date' => $endDateIST->setTimezone('UTC'),
            'duration' => $data['duration'],
            'status' => $data['status'],
            'subject_id' => $data['subject_id'],
            'instructions' => $data['instructions'] ?? null,
        ];

        if ($request->hasFile('instructions_pdf')) {
            $pdfPath = $request->file('instructions_pdf')->store('exam_instructions', 'public');
            $examData['instructions_pdf'] = $pdfPath;
        }

        $exam = Exam::create($examData);

        if (!empty($data['user_ids'])) {
            $exam->users()->attach($data['user_ids']);
        }

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam)
    {
        $users = User::whereIn('role', ['teacher', 'student'])->get();
        $subjects = Subject::all();
        $exam->load('students');
        return view('admin.exams.editExam', compact('exam', 'users', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date|after:start_date|date_format:Y-m-d\TH:i',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'subject_id' => 'required|exists:subjects,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'instructions' => 'nullable|string',
            'instructions_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Convert start_date and end_date from IST to UTC
        $startDateIST = Carbon::parse($data['start_date'], 'Asia/Kolkata');
        $endDateIST = Carbon::parse($data['end_date'], 'Asia/Kolkata');

        $examData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $startDateIST->setTimezone('UTC'),
            'end_date' => $endDateIST->setTimezone('UTC'),
            'duration' => $data['duration'],
            'status' => $data['status'],
            'subject_id' => $data['subject_id'],
            'instructions' => $data['instructions'] ?? null,
        ];

        if ($request->hasFile('instructions_pdf')) {
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $pdfPath = $request->file('instructions_pdf')->store('exam_instructions', 'public');
            $examData['instructions_pdf'] = $pdfPath;
        }

        $exam->update($examData);
        $exam->users()->sync($data['user_ids'] ?? []);

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        if ($exam->instructions_pdf) {
            Storage::disk('public')->delete($exam->instructions_pdf);
        }
        $exam->users()->detach();
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $exam_ids = json_decode($request->input('exam_ids'), true);
        $data = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        Exam::whereIn('id', $exam_ids)->update(['status' => $data['status']]);

        return redirect()->route('admin.exams.index')->with('success', 'Exam status updated successfully.');
    }

    public function instructions(Exam $exam)
    {
        return view('admin.exams.instructions', compact('exam'));
    }

    public function updateInstructions(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'instructions' => 'nullable|string',
            'instructions_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'remove_pdf' => 'nullable|boolean',
        ]);

        $examData = [
            'instructions' => $data['instructions'] ?? null,
        ];

        if ($request->hasFile('instructions_pdf')) {
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $pdfPath = $request->file('instructions_pdf')->store('exam_instructions', 'public');
            $examData['instructions_pdf'] = $pdfPath;
        } elseif ($request->input('remove_pdf')) {
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $examData['instructions_pdf'] = null;
        }

        $exam->update($examData);
        return redirect()->route('admin.exams.instructions', $exam->id)
            ->with('success', 'Exam instructions updated successfully.');
    }

    public function manageQuestions(Exam $exam)
    {
        $exam->load('questions');
        $questions = Question::whereNull('exam_id')->orWhere('exam_id', $exam->id)->with('subject')->get();
        return view('admin.exams.manage-questions', compact('exam', 'questions'));
    }

    public function updateQuestions(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        // Get all questions currently assigned to this exam
        $currentQuestions = $exam->questions()->pluck('id')->toArray();

        // New questions to assign
        $newQuestionIds = $data['question_ids'] ?? [];

        // Questions to remove (in current but not in new)
        $questionsToRemove = array_diff($currentQuestions, $newQuestionIds);
        if (!empty($questionsToRemove)) {
            Question::whereIn('id', $questionsToRemove)->update(['exam_id' => null]);
        }

        // Questions to add (in new but not in current)
        $questionsToAdd = array_diff($newQuestionIds, $currentQuestions);
        if (!empty($questionsToAdd)) {
            Question::whereIn('id', $questionsToAdd)->update(['exam_id' => $exam->id]);
        }

        return redirect()->route('admin.exams.manage-questions', $exam->id)
            ->with('success', 'Questions updated successfully for the exam.');
    }

    public function publish(Exam $exam)
    {
        $exam->update(['published' => true]);

        $students = User::where('role', 'student')->get();
        foreach ($students as $student) {
            $student->notify(new \App\Notifications\ExamPublished($exam));
        }

        return redirect()->back()->with('success', 'Exam published successfully.');
    }
}