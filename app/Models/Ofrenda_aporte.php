<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ofrenda_aporte extends Model
{
    use HasFactory;
    
    public $timestamps = false; 
  
    protected $fillable = [
        'ofrenda_id',          
        'aporte',
        'entregado',
        'fecha',
        'observacion'
    ];

    public function ofrenda()
    {
       return $this->belongsTo('App\Models\Ofrenda');       
    }   
    

}
