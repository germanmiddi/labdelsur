<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waidsession extends Model
{
    protected $fillable = [
        'wa_id',
    ];
    
    use HasFactory;
}
