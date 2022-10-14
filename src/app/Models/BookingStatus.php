<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class BookingStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'booking_status';

    protected $fillable = [
        'status'
    ];

    public function booking()
    {
        return $this->hasMany(booking::class);
    }
}
