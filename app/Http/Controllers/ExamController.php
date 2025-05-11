<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Exam::with('users');

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
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $exam = Exam::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration' => $data['duration'],
            'status' => $data['status'],
        ]);

        if (!empty($data['user_ids'])) {
            $exam->users()->attach($data['user_ids']);
        }

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam)
    {
        $users = User::whereIn('role', ['teacher', 'student'])->get();
        return view('admin.exams.editExam', compact('exam', 'users'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $exam->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration' => $data['duration'],
            'status' => $data['status'],
        ]);

        $exam->users()->sync($data['user_ids'] ?? []);

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
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
}