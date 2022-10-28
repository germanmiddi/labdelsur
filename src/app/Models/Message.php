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
        'wamid',
        'type_msg'
    ];
    
    use HasFactory;

    public function contact(){
        return $this->belongsTo(Contact::class);
    } 

}
