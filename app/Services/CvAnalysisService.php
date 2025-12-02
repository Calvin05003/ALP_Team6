<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Support\Facades\Http;

class CvAnalysisService
{
    public function analyze(string $cvText): array
    {
        // Ambil data divisi + skill dari database
        $divisions = Division::with('skills')->get()->map(function ($division) {
            return [
                'name' => $division->name,
                'description' => $division->description,
                'skills' => $division->skills->map(function ($skill) {
                    return [
                        'skill_name' => $skill->skill_name,
                        'importance_level' => $skill->importance_level,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // SUSUN PROMPT
        $systemPrompt = <<<PROMPT
You are an AI assistant that analyzes student CVs for campus committees.
You must:
- Evaluate CV content, structure, and grammar.
- Identify main skills and experiences from the CV text.
- Match the candidate to the most suitable committees (divisions) based on their skills.
- For each division, give a readiness score (0-100).
- Identify missing or weak skills (skill gaps) per division.
- Provide constructive feedback to improve the CV for campus committee applications.

Return ONLY valid JSON, no explanation text outside JSON.
PROMPT;

        // Data yang kita kirim ke AI
        $userPayload = [
            'cv_text'   => $cvText,
            'divisions' => $divisions,
        ];

        // PANGGIL OPENAI
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4.1-mini', // atau model lain yang kamu pakai
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    [
                        'role' => 'user',
                        'content' => 'Analyze this CV and divisions data: ' . json_encode($userPayload),
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();

        $content = $data['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \RuntimeException('OpenAI response does not contain content.');
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode AI JSON: ' . json_last_error_msg());
        }

        // HARUSNYA struktur seperti ini:
        // {
        //   "resume_score": 80,
        //   "main_skills": [...],
        //   "division_recommendations": [...],
        //   "skill_gaps": [...],
        //   "readiness_scores": [...],
        //   "feedback": "..."
        // }

        return $decoded;
    }
}