<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $classroomFilter = $request->input('classroom_filter');
        $teacherFilter = $request->input('teacher_filter');

        $subjects = Subject::query()
            ->with(['classrooms', 'teachers'])
            ->when($classroomFilter, function ($query, $classroomFilter) {
                return $query->whereHas('classrooms', function ($q) use ($classroomFilter) {
                    $q->where('classrooms.id', $classroomFilter);
                });
            })
            ->when($teacherFilter, function ($query, $teacherFilter) {
                return $query->whereHas('teachers', function ($q) use ($teacherFilter) {
                    $q->where('users.id', $teacherFilter);
                });
            })
            ->paginate(10);

        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.subjects.index', compact('subjects', 'classrooms', 'teachers', 'classroomFilter', 'teacherFilter'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.subjects.create', compact('classrooms', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
        ]);

        if ($request->filled('classroom_id')) {
            $subject->classrooms()->attach($validated['classroom_id']);
        }

        if ($request->filled('teacher_id')) {
            $subject->teachers()->attach($validated['teacher_id']);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();
        $subject->load('classrooms', 'teachers');
        return view('admin.subjects.edit', compact('subject', 'classrooms', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,' . $subject->id],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $subject->update([
            'name' => $validated['name'],
        ]);

        if ($request->filled('classroom_id')) {
            $subject->classrooms()->sync([$validated['classroom_id']]);
        } else {
            $subject->classrooms()->detach();
        }

        if ($request->filled('teacher_id')) {
            $subject->teachers()->sync([$validated['teacher_id']]);
        } else {
            $subject->teachers()->detach();
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->classrooms()->exists() || $subject->teachers()->exists()) {
            return redirect()->route('admin.subjects.index')->with('error', 'Cannot delete subject with existing assignments.');
        }

        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }

    public function assign()
    {
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.subjects.assign', compact('subjects', 'classrooms', 'teachers'));
    }

    public function storeAssignments(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'classroom_ids' => ['nullable', 'array'],
            'classroom_ids.*' => ['exists:classrooms,id'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['exists:users,id'],
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);

        if ($request->filled('classroom_ids')) {
            $subject->classrooms()->sync($validated['classroom_ids']);
        } else {
            $subject->classrooms()->detach();
        }

        if ($request->filled('teacher_ids')) {
            $subject->teachers()->sync($validated['teacher_ids']);
        } else {
            $subject->teachers()->detach();
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject assignments updated successfully.');
    }
}