<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'wa_id',
        'body',
        'menu_selected',
        'status',
        'response',
        'wamid'
    ];
    
    use HasFactory;


}
