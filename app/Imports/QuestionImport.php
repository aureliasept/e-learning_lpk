<?php

namespace App\Imports;

use App\Models\Option;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class QuestionImport implements ToCollection, WithHeadingRow
{
    protected $quizId;
    protected $startOrder;

    public function __construct(int $quizId, int $startOrder = 0)
    {
        $this->quizId = $quizId;
        $this->startOrder = $startOrder;
    }

    public function collection(Collection $rows)
    {
        $order = $this->startOrder;

        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row['pertanyaan'])) {
                continue;
            }

            $order++;

            // Create question
            $question = Question::create([
                'quiz_id' => $this->quizId,
                'question_text' => $row['pertanyaan'],
                'explanation' => $row['pembahasan'] ?? null,
                'order' => $order,
            ]);

            // Get correct answer letter (A, B, C, D, E)
            $correctAnswer = strtoupper(trim($row['jawaban_benar'] ?? 'A'));
            $correctIndex = ord($correctAnswer) - ord('A');

            // Create options
            $optionColumns = ['pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e'];
            
            foreach ($optionColumns as $index => $column) {
                if (!empty($row[$column])) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $row[$column],
                        'is_correct' => $index === $correctIndex,
                    ]);
                }
            }
        }
    }
}
