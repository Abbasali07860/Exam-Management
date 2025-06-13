<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Exports\ExamResultsExport;
use App\Models\ExamResult;
use App\Notifications\ExamResultPublished;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExamResultController extends Controller
{
    public function index()
    {
        $results = ExamResult::with(['exam', 'user'])->paginate(10);
        return view('admin.results.index', compact('results'));
    }

    public function publish(Request $request, ExamResult $result)
    {
        try {
            $published = $request->input('published', false);
            $result->update(['published' => $published]);

            // Send notification to the student if the result is published
            if ($published) {   
                $student = $result->user;
                if ($student) {
                    $student->notify(new ExamResultPublished($result));
                }
            }

            return response()->json([
                'success' => true,
                'message' => $published ? 'Result Published successfully.' : 'Result Unpublished successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update publish status.',
            ], 500);
        }
    }

    public function allow_answers(Request $request, ExamResult $result)
    {
        try {
            $allow_view_answers = $request->input('allow_view_answers', false);
            $result->update(['allow_view_answers' => $allow_view_answers]);

            return response()->json([
                'success' => true,
                'message' => $allow_view_answers ? 'Answers Allowed Successfully.' : 'Answers Disallowed Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update allow status.',
            ], 500);
        }
    }

    public function export($format)
    {
        $results = ExamResult::with(['exam', 'user'])->get();

        if ($format === 'csv') {
            return Excel::download(new ExamResultsExport($results), 'exam_results_' . now()->format('Ymd_His') . '.csv');
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('admin.results.pdf', ['results' => $results]);
            return $pdf->download('exam_results_' . now()->format('Ymd_His') . '.pdf');
        }

        return redirect()->route('admin.results.index')->with('error', 'Invalid export format.');
    }

    public function edit(ExamResult $result)
    {
        $result->load(['answers.question', 'exam', 'user']);
        $subjectiveAnswers = $result->answers->filter(fn($answer) => $answer->question->type === 'subjective');

        return view('admin.results.edit', compact('result', 'subjectiveAnswers'));
    }

    public function update(Request $request, ExamResult $result)
    {
        $result->load('answers.question');
        $subjectiveAnswers = $result->answers->filter(fn($answer) => $answer->question->type === 'subjective');

        $data = $request->validate(
            $subjectiveAnswers->mapWithKeys(fn($answer) => ["marks_obtained.{$answer->id}" => "required|numeric|min:0|max:{$answer->question->marks}"])->toArray()
        );

        $totalScore = $result->answers->sum(function ($answer) use ($data) {
            if ($answer->question->type === 'subjective') {
                $answer->update(['marks_obtained' => $data['marks_obtained'][$answer->id]]);
                return $data['marks_obtained'][$answer->id];
            }
            return $answer->marks_obtained;
        });

        $result->update(['score' => $totalScore]);

        return redirect()->route('admin.results.index')->with('success', 'Marks updated successfully.');
    }
}