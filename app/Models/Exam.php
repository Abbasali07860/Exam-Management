<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'subject_id', 'start_date', 'end_date', 'duration', 'status', 'instructions', 'instructions_pdf',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_id', 'user_id')
            ->where('role', 'student');
    }

    // Accessor for formatted start_date
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('Y-m-d H:i:s');
    }

    // Accessor for formatted end_date
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format('Y-m-d H:i:s');
    }
}