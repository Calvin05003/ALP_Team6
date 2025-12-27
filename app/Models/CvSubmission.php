<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'original_filename',
        'stored_path',
        'extracted_text',
        'input_mode',
        'analysis_mode',
        'language',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke division
    public function division()
    {
        return $this->belongsTo(Division::class); // pastikan ada model Division
    }

    // Relasi ke CV Analysis
    public function analyses()
    {
        return $this->hasMany(CvAnalysis::class);
    }
}
