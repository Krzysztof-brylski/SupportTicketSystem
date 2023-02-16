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
        'assignedAgent',
    ];

    public function Categories(){
        return $this->belongsToMany(Categories::class,'category_id');
    }

    public function labels(){
        return $this->belongsToMany(labels::class,'category_id');
    }
}
