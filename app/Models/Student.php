<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan kolom-kolom tersebut diisi
    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'qr_token',
        'has_voted',
        'voted_at'
    ];
}