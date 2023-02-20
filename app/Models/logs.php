<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logs extends Model
{
    use HasFactory;

    protected $fillable=[
      'actionName',
      'actionTime'
    ];
    protected $visible=[
        'actionName','actionTime','user_id','id'
    ];
    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function logable(){
        return $this->morphTo();
    }

}
