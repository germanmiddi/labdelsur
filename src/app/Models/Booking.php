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

    public function contact(){
        return $this->belongsTo(Contact::class);
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class);
    }
}
