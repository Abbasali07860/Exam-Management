<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ExamResult;

class ExamResultPublished extends Notification
{
    use Queueable;

    protected $examResult;

    public function __construct(ExamResult $examResult)
    {
        $this->examResult = $examResult;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'exam_id' => $this->examResult->exam_id,
            'exam_title' => $this->examResult->exam->title,
            'score' => $this->examResult->score,
            'message' => "Exam Result for '{$this->examResult->exam->title}' has been published. Your score: {$this->examResult->score}.",
        ];
    }
}