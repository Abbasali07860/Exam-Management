@extends('layouts.user')

@section('title', 'Start Exam - ' . $exam->title)

@section('content')
<div class="min-h-screen">
    <div class="max-w-5xl mx-auto animate-fade-in">
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ $exam->title }}</h2>
                <p class="text-slate-600 text-base mt-2 tracking-wide">Subject: {{ $exam->subject->name ?? 'N/A' }} | Duration: {{ $exam->duration }} minutes</p>
            </div>
            <div class="text-sm text-slate-600 bg-slate-100 px-4 py-2 rounded-lg">
                Started at: {{ $startTime->format('Y-m-d H:i:s') }}
            </div>
        </div>

        <!-- Exam Content -->
        <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
            <!-- Timer and Progress -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clock text-amber-500 text-xl"></i>
                    <h3 class="text-lg font-semibold text-slate-800">Time Remaining: <span id="timer" class="text-teal-600">{{ $exam->duration }}:00</span></h3>
                </div>
                <div class="w-full sm:w-1/3">
                    <div class="text-sm text-slate-600 mb-1">Progress: <span id="progress-text">0%</span></div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-teal-500 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Toggle View -->
            <div class="flex items-center justify-end mb-6">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" id="view-all-toggle" class="text-indigo-600 focus:ring-indigo-500">
                    <span>View all questions</span>
                </label>
            </div>

            <!-- Exam Form -->
            <form id="exam-form" action="{{ route('student.exams.submit', $exam) }}" method="POST">
                @csrf
                <input type="hidden" name="start_time" value="{{ $startTime->format('Y-m-d H:i:s') }}">
                <input type="hidden" name="exam_result_id" value="{{ $examResult->id }}">
                <!-- Questions -->
                <div id="questions-container">
                    @foreach($questions as $index => $question)
                        <div class="question-card {{ $index > 0 ? 'hidden' : '' }} p-6 bg-slate-50 rounded-lg shadow-sm border border-slate-200 mb-6" data-index="{{ $index }}" data-question-id="{{ $question->id }}">
                            <p class="text-base font-medium text-slate-800 mb-3">
                                {{ $index + 1 }}. {{ $question->question_text }} <span class="text-sm text-slate-600">({{ $question->marks }} marks)</span>
                            </p>

                            @if($question->type === 'mcq')
                                <div class="space-y-3">
                                    @foreach($question->options as $optionKey => $optionText)
                                        <label class="flex items-center gap-2 text-slate-600">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optionKey }}" class="text-indigo-600 focus:ring-indigo-500" onchange="updateProgress(this)">
                                            <span>{{ $optionText }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <textarea name="answers[{{ $question->id }}]" rows="4" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-slate-800" placeholder="Type your answer here..." oninput="updateProgress(this)"></textarea>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons (for one-at-a-time view) -->
                <div id="navigation-buttons" class="flex justify-between mb-6">
                    <button type="button" id="prev-button" class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-100 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left mr-2"></i> Previous
                    </button>
                    <button type="button" id="next-button" class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-100 transition-all duration-200">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('student.exams') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-100 transition-all duration-200">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="button" onclick="confirmSubmit()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-check"></i>
                        Submit Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Exam Security
let examSubmitted = false;

// Prevent back button
window.history.pushState(null, null, window.location.href);
window.onpopstate = function () {
    if (!examSubmitted) {
        window.history.pushState(null, null, window.location.href);
        alert('You cannot go back during the exam. Please complete the exam or submit it.');
    }
};

// Prevent tab switching
document.addEventListener('visibilitychange', function () {
    if (document.hidden && !examSubmitted) {
        alert('Tab switching is not allowed during the exam. Please stay on this page.');
    }
});

// Prevent right-click (context menu)
document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
    alert('Right-click is disabled during the exam.');
});

// Prevent certain keyboard shortcuts (e.g., Ctrl+U, Ctrl+S)
document.addEventListener('keydown', function (e) {
    if (e.ctrlKey && (e.key === 'u' || e.key === 's')) {
        e.preventDefault();
        alert('Keyboard shortcuts like Ctrl+U or Ctrl+S are disabled during the exam.');
    }
});

// Timer Logic
let duration = {{ $exam->duration * 60 }}; // Duration in seconds (initial value for display)
const timerElement = document.getElementById('timer');
const progressBar = document.getElementById('progress-bar');
const progressText = document.getElementById('progress-text');
const totalDuration = duration;

// Use the exam's official start date and append UTC to ensure correct parsing
const examStartTime = new Date('{{ $exam->start_date->format('Y-m-d H:i:s') }} UTC').getTime();
const examEndTime = examStartTime + ({{ $exam->duration * 60 }} * 1000);

// Calculate initial duration based on current time
const now = new Date().getTime();
duration = Math.max(0, Math.floor((examEndTime - now) / 1000));

// Display initial time
let minutes = Math.floor(duration / 60);
let seconds = duration % 60;
timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

// Start the timer countdown
let timer = setInterval(() => {
    const currentTime = new Date().getTime();
    duration = Math.max(0, Math.floor((examEndTime - currentTime) / 1000));

    if (duration <= 0) {
        clearInterval(timer);
        alert('Time is up! Submitting your exam.');
        examSubmitted = true;
        document.getElementById('exam-form').submit();
    }

    minutes = Math.floor(duration / 60);
    seconds = duration % 60;
    timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
}, 1000);

// Question Navigation
const questions = document.querySelectorAll('.question-card');
const prevButton = document.getElementById('prev-button');
const nextButton = document.getElementById('next-button');
const viewAllToggle = document.getElementById('view-all-toggle');
let currentQuestionIndex = 0;

function updateNavigation() {
    questions.forEach((question, index) => {
        question.classList.add('hidden');
        if (viewAllToggle.checked || index === currentQuestionIndex) {
            question.classList.remove('hidden');
        }
    });

    prevButton.disabled = currentQuestionIndex === 0;
    nextButton.disabled = currentQuestionIndex === questions.length - 1;

    if (viewAllToggle.checked) {
        prevButton.classList.add('hidden');
        nextButton.classList.add('hidden');
    } else {
        prevButton.classList.remove('hidden');
        nextButton.classList.remove('hidden');
    }
}

prevButton.addEventListener('click', () => {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        updateNavigation();
    }
});

nextButton.addEventListener('click', () => {
    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        updateNavigation();
    }
});

viewAllToggle.addEventListener('change', () => {
    updateNavigation();
});

// Progress Calculation and Auto-save
function updateProgress(element) {
    let answeredQuestions = 0;
    const totalQuestions = questions.length;

    questions.forEach((question, index) => {
        const inputs = question.querySelectorAll('input[type="radio"]:checked, textarea');
        inputs.forEach(input => {
            if (input.type === 'radio' || (input.type === 'textarea' && input.value.trim() !== '')) {
                answeredQuestions++;
            }
        });
    });

    const progress = (answeredQuestions / totalQuestions) * 100;
    progressBar.style.width = `${progress}%`;
    progressText.textContent = `${Math.round(progress)}%`;

    // Auto-save answer
    if (element) {
        const questionCard = element.closest('.question-card');
        const questionId = questionCard.dataset.questionId;
        let answer = '';

        if (element.type === 'radio') {
            answer = element.value;
        } else if (element.type === 'textarea') {
            answer = element.value;
        }

        fetch('{{ route('student.exams.save-answer', $exam) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                question_id: questionId,
                answer: answer,
                exam_result_id: '{{ $examResult->id }}',
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Auto-save failed:', data.error);
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    }
}

// Confirm Submission
function confirmSubmit() {
    if (confirm('Are you sure you want to submit the exam? You cannot make changes after submission.')) {
        clearInterval(timer);
        examSubmitted = true;
        document.getElementById('exam-form').submit();
    }
}

// Initial setup
updateNavigation();
</script>
@endsection