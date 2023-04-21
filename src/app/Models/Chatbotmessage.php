<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatbotmessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'step',
        'message',
    ];

    // protected $casts = [
    //     'step'      => 'array',
    // ];

}
