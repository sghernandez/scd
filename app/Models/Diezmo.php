<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diezmo extends Model
{
    use HasFactory;   

    protected $fillable = [
        'observacion', 
        'diezmo', 
        'ofrenda',
        'cancelado',
        'fecha',
        'user_id'
    ];

    public function user()
    {
       return $this->belongsTo('App\Models\User');       
    }   

}
