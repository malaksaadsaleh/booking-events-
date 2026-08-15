<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];

    public function user(){
            return $this->belongsTo( User::class);
        }
    
    public function event(){
            return $this->belongsTo( Event::class);
        }     
    
    protected function Scopepending(Builder $query){
        return $query->where('status' , "pending");
    }    
}
