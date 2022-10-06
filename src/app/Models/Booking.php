<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'status',
        'contact_id'
    ];

    public function contact(){
        return $this->belongsTo(Contact::class);
    }
}
