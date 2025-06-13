<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with('subjects')->paginate(5);
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $subjects = Subject::all(); // Fetch all subjects for assignment
        return view('admin.classrooms.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:classrooms,name'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'], // Validate each subject ID
        ]);

        $classroom = Classroom::create([
            'name' => $validated['name'],
        ]);

        // Attach subjects if selected
        if ($request->filled('subject_ids')) {
            $classroom->subjects()->sync($validated['subject_ids']);
        }

        return redirect()->route('admin.classrooms.index')->with('success', 'Class created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        $subjects = Subject::all(); // Fetch all subjects for assignment
        $classroom->load('subjects'); // Load the currently assigned subjects
        return view('admin.classrooms.edit', compact('classroom', 'subjects'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:classrooms,name,' . $classroom->id],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
        ]);

        $classroom->update([
            'name' => $validated['name'],
        ]);

        // Sync subjects (update assignments)
        if ($request->filled('subject_ids')) {
            $classroom->subjects()->sync($validated['subject_ids']);
        } else {
            $classroom->subjects()->detach(); // Remove all subjects if none selected
        }

        return redirect()->route('admin.classrooms.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        if ($classroom->subjects()->exists()) {
            return redirect()->route('admin.classrooms.index')->with('error', 'Cannot delete class with assigned subjects.');
        }

        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Class deleted successfully.');
    }
}