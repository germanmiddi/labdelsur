<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDay extends Model
{
    protected $table = 'details_days';
    protected $fillable = [
        'num_day',
        'cant_orders',
        'description'
    ];
    
    use HasFactory;
}
