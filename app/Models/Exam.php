<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'duration',
        'status',
        'instructions',
        'instructions_pdf',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'exam_user')
                    ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'exam_user')
                    ->where('role', 'student')
                    ->withTimestamps();
    }

    // Accessor for formatted start_date
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('Y-m-d H:i') : 'N/A';
    }

    // Accessor for formatted end_date
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('Y-m-d H:i') : 'N/A';
    }
}