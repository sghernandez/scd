<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ofrenda extends Model
{
    use HasFactory;
    
    public $timestamps = false; 
  
    protected $fillable = [
        'user_id',          
        'periocidad',
        'fecha',
        'detalle'
    ];

    public function user()
    {
       return $this->belongsTo('App\Models\User');       
    }   

}
