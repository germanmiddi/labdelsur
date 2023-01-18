<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    use HasFactory;
    protected $table = 'obras_sociales';
    
    protected $fillable = [
        'title',
        'description',
        'visible',
        'foverite',
        'url'
    ];
}
