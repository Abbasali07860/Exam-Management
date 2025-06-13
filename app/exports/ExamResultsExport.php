<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ExamResultsExport implements FromCollection, WithHeadings
{
    protected $results;

    public function __construct(Collection $results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results->map(function ($result) {
            return [
                'Exam Name' => $result->exam->title,
                'Student Name' => $result->user->name,
                'Score' => $result->score,
                'Date Attempted' => $result->end_time->format('Y-m-d'),
                'Published' => $result->published ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Exam Name',
            'Student Name',
            'Score',
            'Date Attempted',
            'Published',
        ];
    }
}