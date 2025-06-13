<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'image', 'status', 'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasAnyRole($roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_results', 'user_id', 'exam_id')
            ->withPivot('score', 'start_time', 'end_time', 'published', 'allow_view_answers')
            ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}