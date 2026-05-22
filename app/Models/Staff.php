<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'nama',
        'jabatan',
        'no_telp',
        'email',
        'foto'
    ];
}