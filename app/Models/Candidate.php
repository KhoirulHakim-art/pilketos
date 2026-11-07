<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_urut',
        'nama',
        'visi',
        'misi',
        'foto',
        'total_votes',
    ];
}