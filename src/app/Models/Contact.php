<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'wa_id',
        'name'
    ];

    use HasFactory;

    public function messages(){
        return $this->hasMany(Message::class);   
    }    

    public function bookings(){
        return $this->hasMany(Booking::class);   
    } 
    
}
