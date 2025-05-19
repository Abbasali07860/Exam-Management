<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\Controller;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Exam::with(['users', 'students']);

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
        return view('admin.exams.createExam', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date|after:start_date|date_format:Y-m-d H:i:s',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'instructions' => 'nullable|string',
            'instructions_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $examData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration' => $data['duration'],
            'status' => $data['status'],
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
        $students = User::where('role', 'student')->get();
        return view('admin.exams.editExam', compact('exam', 'users', 'students'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date|after:start_date|date_format:Y-m-d H:i:s',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
            'instructions' => 'nullable|string',
            'instructions_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $examData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration' => $data['duration'],
            'status' => $data['status'],
            'instructions' => $data['instructions'] ?? null,
        ];

        if ($request->hasFile('instructions_pdf')) {
            // Delete the old PDF if it exists
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $pdfPath = $request->file('instructions_pdf')->store('exam_instructions', 'public');
            $examData['instructions_pdf'] = $pdfPath;
        }

        $exam->update($examData);
        $exam->students()->sync($data['student_ids'] ?? []);

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        // Delete the PDF if it exists
        if ($exam->instructions_pdf) {
            Storage::disk('public')->delete($exam->instructions_pdf);
        }
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
            // Delete the old PDF if it exists
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $pdfPath = $request->file('instructions_pdf')->store('exam_instructions', 'public');
            $examData['instructions_pdf'] = $pdfPath;
        } elseif ($request->input('remove_pdf')) {
            // Remove the PDF if the checkbox is checked
            if ($exam->instructions_pdf) {
                Storage::disk('public')->delete($exam->instructions_pdf);
            }
            $examData['instructions_pdf'] = null;
        }

        $exam->update($examData);

        return redirect()->route('admin.exams.instructions', $exam->id)
            ->with('success', 'Exam instructions updated successfully.');
    }
}