<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'description',
        'files',
        'priority',
        'status',
    ];

    public function Author(){
        return $this->belongsTo(User::class,'author_id');
    }

    public function Agent(){
        return $this->belongsTo(User::class,'agent_id');
    }

    public function Categories(){
        return $this->belongsTo(Categories::class,'category_id');
    }

    public function labels(){
        return $this->belongsTo(labels::class,'label_id');
    }
}
