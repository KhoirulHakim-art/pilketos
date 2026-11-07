<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan pembuatan data suara
    protected $fillable = [
        'student_id',
        'candidate_id',
    ];

    // Relasi ke Model Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Model Candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}