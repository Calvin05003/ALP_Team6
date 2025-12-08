<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Support\Facades\Http;

class CvAnalysisService
{
    public function analyze(string $cvText): array
    {
        // 1. Ambil data divisi + skill dari database
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

        // 2. System prompt (perilaku AI)
        $systemPrompt = <<<PROMPT
You are an AI assistant that analyzes student CVs for campus committees.

You must:
- Evaluate CV content, structure, and grammar.
- Identify main skills and experiences from the CV text.
- Match the candidate to the most suitable committees (divisions) based on their skills.
- For each division, give a readiness score (0-100).
- Identify missing or weak skills (skill gaps) per division.
- Provide constructive feedback to improve the CV for campus committee applications.

You MUST respond ONLY with valid JSON (no markdown, no extra text) using this structure:

{
  "resume_score": number,
  "main_skills": [string],
  "division_recommendations": [
    {
      "division_name": string,
      "reason": string
    }
  ],
  "readiness_scores": [
    {
      "division_name": string,
      "score": number
    }
  ],
  "skill_gaps": [
    {
      "division_name": string,
      "missing_skills": [string]
    }
  ],
  "feedback": string
}
PROMPT;

        // 3. Ambil API key Groq
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            throw new \RuntimeException('GROQ_API_KEY belum di-set di file .env');
        }

        // 4. Endpoint Groq (OpenAI-compatible)
        $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

        // 5. Susun body request
        $userContent = "You are given:\n\n"
            . "1) Division and skill data (in JSON):\n"
            . json_encode($divisions, JSON_PRETTY_PRINT)
            . "\n\n2) The raw CV text to analyze:\n"
            . $cvText
            . "\n\nAnalyze the CV and return ONLY the JSON using the required structure.";

        $body = [
            'model' => 'llama-3.3-70b-versatile', // bisa diganti model lain yang kamu mau
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userContent,
                ],
            ],
            // JSON Mode: pastikan balikan valid JSON
            'response_format' => [
                'type' => 'json_object',
            ],
            'temperature' => 0.2,
            'max_completion_tokens' => 1024,
        ];

        // 6. Panggil Groq
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])
            ->timeout(60)
            ->post($endpoint, $body);

        if ($response->failed()) {
            throw new \RuntimeException('Groq API error: ' . $response->body());
        }

        $data = $response->json();

        // Struktur mirip OpenAI: choices[0].message.content
        $content = $data['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \RuntimeException('Groq response does not contain content.');
        }

        // 7. Decode JSON string yang dikirim model
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Failed to decode AI JSON. Error: ' . json_last_error_msg() . '. Raw content: ' . $content
            );
        }

        return $decoded;
    }
}