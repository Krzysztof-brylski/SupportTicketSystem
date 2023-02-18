<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coments extends Model
{
    use HasFactory;
    protected $fillable=[
        'content'
    ];
    public function Author(){
        return $this->belongsTo(User::class,'author_id');
    }
    public function Ticket(){
        return $this->belongsTo(Ticket::class,'ticket_id');
    }
}
