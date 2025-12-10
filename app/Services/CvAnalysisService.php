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
                'name'        => $division->name,
                'description' => $division->description,
                'skills'      => $division->skills->map(function ($skill) {
                    return [
                        'skill_name'       => $skill->skill_name,
                        'importance_level' => $skill->importance_level,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // 2. System prompt (perilaku AI) — versi lebih kaya insight
        $systemPrompt = <<<PROMPT
You are an AI assistant that analyzes student CVs for campus committees.

You must:
- Evaluate CV content, structure, clarity, and grammar.
- Identify main skills, experiences, and achievements from the CV text.
- Match the candidate to the most suitable committees (divisions) based on their skills and experiences.
- For each division, give a readiness score (0-100) and a keyword_match score (0-100) based on how well the CV matches the division's required skills.
- Identify missing or weak skills (skill gaps) per division.
- Highlight the strengths and weaknesses of the CV.
- Identify missing or incomplete sections (e.g., no education, no contact info, no experience, no summary).
- Estimate the overall experience level of the candidate (Beginner, Intermediate, or Strong) for campus committee work.
- Provide concrete, actionable suggestions to improve the CV for campus committee applications (suggested_improvements).
- Provide a final narrative feedback summary (feedback) that combines your main observations.

You MUST respond ONLY with valid JSON (no markdown, no extra text) using EXACTLY this structure:

{
  "resume_score": number,
  "ats_score": number,
  "experience_level": "Beginner" | "Intermediate" | "Strong",

  "main_skills": [string],
  "achievements": [string],

  "division_recommendations": [
    {
      "division_name": string,
      "reason": string,
      "keyword_match": number
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

  "strengths": [string],
  "weaknesses": [string],
  "missing_sections": [string],

  "grammar_issues": {
    "critical": number,
    "minor": number,
    "spelling": number
  },

  "suggested_improvements": [string],

  "feedback": string
}

Important rules:
- Use only plain JSON (no comments, no trailing commas, no markdown).
- Always include all keys above, even if some arrays are empty or some numbers are 0.
- "ats_score", "resume_score", "keyword_match", and "readiness_scores[*].score" MUST be between 0 and 100.
- "experience_level" MUST be exactly one of: "Beginner", "Intermediate", "Strong".
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
            . "\n\nAnalyze the CV strictly following the JSON schema described in the system message. "
            . "Return ONLY the JSON object, nothing else.";

        $body = [
            'model' => 'llama-3.3-70b-versatile', // bisa diganti model lain yang kamu mau
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role'    => 'user',
                    'content' => $userContent,
                ],
            ],
            // JSON Mode: pastikan balikan valid JSON
            'response_format' => [
                'type' => 'json_object',
            ],
            'temperature'            => 0.2,
            'max_completion_tokens'  => 1024,
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