<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'person_id',
        'country_id',
        'state_id',
        'city_id',
        'zipcode',
        'street',
        'strnum',
        'floor',
        'appartment',
        'notes'
    ];

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }    

    public function state(){
        return $this->belongsTo(State::class);
    }    

    public function city(){
        return $this->belongsTo(City::class);
    }    
    

}
