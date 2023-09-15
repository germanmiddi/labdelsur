<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'status_id',
        'contact_id'
    ];

    // public function contact(){
    //     return $this->belongsTo(Contact::class);
    // }

    public function bookingContact(){
        return $this->belongsTo(BookingContact::class, 'contact_id');
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class);
    }
}
