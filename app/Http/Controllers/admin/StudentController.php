<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamAnswer;
use Carbon\Carbon;

class StudentController extends Controller
{
    private const PROFILE_IMAGE_PATH = 'profile_images';
    private const PASSING_PERCENTAGE = 40;

    public function index()
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now();

        $totalAvailableExams = Exam::where('start_date', '>=', $currentDateTime)
            ->where('status', 'active')
            ->whereDoesntHave('results', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();
        $totalAttemptedExams = ExamResult::where('user_id', $user->id)->count();

        $recentResults = ExamResult::where('user_id', $user->id)
            ->with('exam')
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact('user', 'totalAvailableExams', 'totalAttemptedExams', 'recentResults'));
    }

    public function profile()
    {
        return view('student.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($user->image) {
                Storage::disk('public')->delete(self::PROFILE_IMAGE_PATH . '/' . $user->image);
            }
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs(self::PROFILE_IMAGE_PATH, $imageName, 'public');
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
    }

    public function exams()
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now();

        $exams = Exam::where('start_date', '>=', $currentDateTime)
            ->where('status', 'active')
            ->whereDoesntHave('results', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('subject')
            ->paginate(10);

        return view('student.exams', compact('exams'));
    }

    public function instructions(Exam $exam)
    {
        return view('student.instructions', compact('exam'));
    }

    public function startExam(Exam $exam)
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now()->setTimezone('UTC');

        // Ensure exam start_date is in UTC for comparison
        $examStartDate = $exam->start_date->setTimezone('UTC');

        // Truncate both timestamps to seconds to ignore microseconds
        $currentDateTime = $currentDateTime->startOfSecond();
        $examStartDate = $examStartDate->startOfSecond();

        // Check if the exam start date is in the future
        // if ($examStartDate->gt($currentDateTime)) {
        //     // Convert start date to IST for display
        //     $startDateInIST = $exam->start_date->setTimezone('Asia/Kolkata');
        //     return redirect()->route('student.exams')->with('error', 'This exam is not yet available. It will start at ' . $startDateInIST->format('Y-m-d H:i:s') . ' IST.');
        // }

        // Check if the exam has ended
        $examEndDate = $exam->end_date->setTimezone('UTC')->startOfSecond();
        if ($examEndDate->lt($currentDateTime)) {
            return redirect()->route('student.exams')->with('error', 'This exam has already ended.');
        }

        if ($exam->results()->where('user_id', $user->id)->exists()) {
            return redirect()->route('student.exams')->with('error', 'You have already attempted this exam.');
        }

        $exam->load('questions');

        // if ($exam->questions->isEmpty()) {
        //     return redirect()->route('student.exams')->with('error', 'No questions available for this exam.');
        // }

        // Create an ExamResult record to track the attempt
        $examResult = ExamResult::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'score' => 0,
            'start_time' => $currentDateTime,
            'end_time' => null,
            'published' => false,
            'allow_view_answers' => false,
        ]);

        // Randomize questions
        $questions = $exam->questions->shuffle();

        // Randomize MCQ options
        foreach ($questions as $question) {
            if ($question->type === 'mcq') {
                $question->options = collect($question->options)->shuffle()->toArray();
            }
        }

        $startTime = $currentDateTime;

        return view('student.exam-start', compact('exam', 'questions', 'startTime', 'examResult'));
    }

    public function saveAnswer(Request $request, Exam $exam)
    {
        $user = Auth::user();

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'nullable|string',
            'exam_result_id' => 'required|exists:exam_results,id',
        ]);

        $examResult = ExamResult::findOrFail($request->exam_result_id);

        // Verify the exam result belongs to the user and exam
        if ($examResult->user_id !== $user->id || $examResult->exam_id !== $exam->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if the exam has already been submitted
        if ($examResult->end_time) {
            return response()->json(['error' => 'Exam already submitted'], 400);
        }

        // Update or create the answer
        ExamAnswer::updateOrCreate(
            [
                'exam_result_id' => $examResult->id,
                'question_id' => $request->question_id,
            ],
            [
                'answer' => $request->answer,
                'marks_obtained' => 0,
            ]
        );

        return response()->json(['success' => 'Answer saved successfully']);
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now();

        $request->validate([
            'start_time' => 'required|date',
            'exam_result_id' => 'required|exists:exam_results,id',
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        $examResult = ExamResult::findOrFail($request->exam_result_id);

        // Verify the exam result belongs to the user and exam
        if ($examResult->user_id !== $user->id || $examResult->exam_id !== $exam->id) {
            return redirect()->route('student.exams')->with('error', 'Unauthorized access to exam result.');
        }

        // Check if already submitted
        if ($examResult->end_time) {
            return redirect()->route('student.exams')->with('error', 'You have already submitted this exam.');
        }

        $exam->load('questions');

        $totalScore = 0;
        $answers = $request->input('answers', []);

        foreach ($exam->questions as $question) {
            $answerText = $answers[$question->id] ?? null;

            if ($answerText === null) {
                continue;
            }

            $marksObtained = 0;

            if ($question->type === 'mcq') {
                $correctOptionKey = array_search($question->correct_answer, $question->options);
                if ($answerText == $correctOptionKey) {
                    $marksObtained = $question->marks;
                    $totalScore += $marksObtained;
                }
            }

            ExamAnswer::updateOrCreate(
                [
                    'exam_result_id' => $examResult->id,
                    'question_id' => $question->id,
                ],
                [
                    'answer' => $answerText,
                    'marks_obtained' => $marksObtained,
                ]
            );
        }

        $examResult->update([
            'score' => $totalScore,
            'end_time' => $currentDateTime,
        ]);

        return redirect()->route('student.thank-you')->with('success', 'Exam submitted successfully! Your results will be available once published by the admin.');
    }

    public function results()
    {
        $user = Auth::user();

        $results = ExamResult::where('user_id', $user->id)
            ->where('published', true)
            ->with('exam')
            ->latest('end_time')
            ->get();

        return view('student.results', compact('results'));
    }

    public function thankYou()
    {
        return view('student.thank-you');
    }

    public function showResult(ExamResult $examResult)
    {
        $user = Auth::user();

        if ($examResult->user_id !== $user->id || !$examResult->published) {
            return redirect()->route('student.results')->with('error', 'You are not authorized to view this result or it is not yet published.');
        }

        $examResult->load(['exam.questions', 'answers.question']);

        $totalQuestions = $examResult->exam->questions->count();
        $totalMarks = $examResult->exam->questions->sum('marks');
        $score = $examResult->score;
        $percentage = $totalMarks > 0 ? ($score / $totalMarks) * 100 : 0;

        $stats = $examResult->answers->reduce(function ($carry, $answer) {
            $carry['correct'] += $answer->marks_obtained > 0 ? 1 : 0;
            $carry['wrong'] += $answer->marks_obtained == 0 ? 1 : 0;
            return $carry;
        }, ['correct' => 0, 'wrong' => 0]);

        $passStatus = $percentage >= self::PASSING_PERCENTAGE ? 'Pass' : 'Fail';

        return view('student.result-details', compact(
            'examResult',
            'totalQuestions',
            'stats',
            'score',
            'percentage',
            'passStatus',
            'totalMarks'
        ));
    }

    public function showAnswers(ExamResult $examResult)
    {
        $user = Auth::user();

        if ($examResult->user_id !== $user->id || !$examResult->published || !$examResult->allow_view_answers) {
            return redirect()->route('student.results.show', $examResult)
                ->with('error', 'You are not authorized to view the answers for this result.');
        }

        $examResult->load(['exam.questions', 'answers.question']);

        return view('student.result-answers', compact('examResult'));
    }
}