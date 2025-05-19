<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $type = strtolower(trim($row['type']));
        if (!in_array($type, ['mcq', 'true_false', 'descriptive'])) {
            return null;
        }
        $options = null;
        if ($type === 'mcq') {
            if (isset($row['option_a'], $row['option_b'], $row['option_c'], $row['option_d'])) {
                $options = [
                    trim($row['option_a']),
                    trim($row['option_b']),
                    trim($row['option_c']),
                    trim($row['option_d']),
                ];
                if (in_array('', $options, true)) {
                    return null;
                }
            } 
            elseif (!empty($row['options'])) {
                $options = array_map('trim', explode(',', $row['options']));
                if (count($options) < 4) {
                    return null;
                }
            } else {
                return null; 
            }
        }

        $correctAnswer = trim($row['correct_answer'] ?? '');

        if ($type === 'mcq' && !in_array($correctAnswer, ['0', '1', '2', '3'])) {
            return null;
        }

        if ($type === 'true_false' && !in_array(strtolower($correctAnswer), ['true', 'false'])) {
            return null;
        }
        $subjectId = isset($row['subject_id']) ? (int)$row['subject_id'] : null;
        if ($subjectId && !Subject::find($subjectId)) {
            $subjectId = null;
        }
        $marks = (int)($row['marks'] ?? 2);
        if ($marks < 2) {
            $marks = 2;
        }

        return new Question([
            'subject_id'     => $subjectId,
            'type'           => $type,
            'question_text'  => $row['question_text'] ?? '',
            'options'        => $options, // Casted automatically by model
            'correct_answer' => $correctAnswer,
            'marks'          => $marks,
        ]);
    }
}
