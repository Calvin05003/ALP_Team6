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
    ];

    public function analysis()
    {
        return $this->hasOne(CvAnalysis::class);
    }
}