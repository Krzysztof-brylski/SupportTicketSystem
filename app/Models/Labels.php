<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
    ];
   Public function Ticket(){
       return $this->hasMany(Ticket::class);
   }
}
