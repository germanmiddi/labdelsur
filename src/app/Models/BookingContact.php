<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingContact extends Model
{
    use HasFactory;
    protected $table = 'booking_contacts';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'nro_afiliado'
    ];

    public function bookings(){
        return $this->hasMany(Booking::class);   
    }     
}
