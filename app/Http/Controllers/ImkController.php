<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImkController extends Controller
{
    public function sus(): View
    {
        $responses = $this->loadSusResponses();
        $summary = $this->buildSummary($responses);

        return view('imk.sus', [
            'summary' => $summary,
            'formUrl' => env('IMK_SUS_FORM_URL', ''),
            'sheetUrl' => env('IMK_SUS_SHEET_URL', ''),
        ]);
    }

    public function visualization(): View
    {
        $responses = $this->loadSusResponses();
        $summary = $this->buildSummary($responses);

        return view('imk.visualization', [
            'summary' => $summary,
            'formUrl' => env('IMK_SUS_FORM_URL', ''),
            'sheetUrl' => env('IMK_SUS_SHEET_URL', ''),
        ]);
    }

    public function downloadResponses(): BinaryFileResponse
    {
        $filePath = base_path('database/data/imk/sus_responses_sample.csv');

        abort_unless(file_exists($filePath), 404);

        return response()->download($filePath, 'sus_responses_sample.csv');
    }

    public function downloadQuestionBank(): BinaryFileResponse
    {
        $filePath = base_path('database/data/imk/sus_question_bank.md');

        abort_unless(file_exists($filePath), 404);

        return response()->download($filePath, 'sus_question_bank.md');
    }

    /**
     * @return array<int, array<string, int|string|float>>
     */
    private function loadSusResponses(): array
    {
        $filePath = base_path('database/data/imk/sus_responses_sample.csv');

        if (!file_exists($filePath)) {
            return [];
        }

        $file = fopen($filePath, 'r');
        if ($file === false) {
            return [];
        }

        $header = fgetcsv($file);
        if ($header === false) {
            fclose($file);
            return [];
        }

        $rows = [];
        while (($data = fgetcsv($file)) !== false) {
            if (count($data) !== count($header)) {
                continue;
            }

            $row = array_combine($header, $data);
            if ($row === false) {
                continue;
            }

            for ($i = 1; $i <= 10; $i++) {
                $key = 'q' . $i;
                $row[$key] = (int) ($row[$key] ?? 0);
            }

            $row['sus_score'] = $this->calculateSusScore($row);
            $rows[] = $row;
        }

        fclose($file);

        return $rows;
    }

    /**
     * @param array<string, int|string|float> $row
     */
    private function calculateSusScore(array $row): float
    {
        $score = 0;

        for ($i = 1; $i <= 10; $i++) {
            $key = 'q' . $i;
            $value = (int) ($row[$key] ?? 0);

            if ($i % 2 === 1) {
                $score += max(0, min(4, $value - 1));
            } else {
                $score += max(0, min(4, 5 - $value));
            }
        }

        return round($score * 2.5, 2);
    }

    /**
     * @param array<int, array<string, int|string|float>> $responses
     * @return array<string, mixed>
     */
    private function buildSummary(array $responses): array
    {
        $total = count($responses);
        $real = 0;
        $dummy = 0;
        $totalScore = 0;
        $questionSums = array_fill(1, 10, 0);

        foreach ($responses as $response) {
            $source = strtolower((string) ($response['source_label'] ?? ''));
            if ($source === 'real') {
                $real++;
            }
            if ($source === 'dummy') {
                $dummy++;
            }

            $totalScore += (float) $response['sus_score'];

            for ($i = 1; $i <= 10; $i++) {
                $questionSums[$i] += (int) ($response['q' . $i] ?? 0);
            }
        }

        $averageScore = $total > 0 ? round($totalScore / $total, 2) : 0;
        $interpretation = $this->interpretSusScore($averageScore);

        $questionAverages = [];
        for ($i = 1; $i <= 10; $i++) {
            $questionAverages['Q' . $i] = $total > 0 ? round($questionSums[$i] / $total, 2) : 0;
        }

        $scoreBuckets = [
            '< 50' => 0,
            '50 - 67.9' => 0,
            '68 - 79.9' => 0,
            '>= 80' => 0,
        ];

        foreach ($responses as $response) {
            $susScore = (float) $response['sus_score'];
            if ($susScore < 50) {
                $scoreBuckets['< 50']++;
            } elseif ($susScore < 68) {
                $scoreBuckets['50 - 67.9']++;
            } elseif ($susScore < 80) {
                $scoreBuckets['68 - 79.9']++;
            } else {
                $scoreBuckets['>= 80']++;
            }
        }

        return [
            'total' => $total,
            'real' => $real,
            'dummy' => $dummy,
            'average_score' => $averageScore,
            'grade' => $interpretation['grade'],
            'adjective' => $interpretation['adjective'],
            'question_averages' => $questionAverages,
            'score_buckets' => $scoreBuckets,
        ];
    }

    /**
     * @return array{grade:string, adjective:string}
     */
    private function interpretSusScore(float $score): array
    {
        if ($score >= 80) {
            return ['grade' => 'A', 'adjective' => 'Excellent'];
        }
        if ($score >= 68) {
            return ['grade' => 'B', 'adjective' => 'Good'];
        }
        if ($score >= 50) {
            return ['grade' => 'C', 'adjective' => 'OK'];
        }
        return ['grade' => 'D', 'adjective' => 'Poor'];
    }
}
