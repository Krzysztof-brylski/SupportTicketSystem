<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected $observables = ['assignAgent','updateStatus'];

    public function assignAgent(User $agent){
        DB::transaction(function () use( $agent){
            $this->Agent()->associate($agent);
            $this->save();
            $this->update([
                'status'=>StatusEnum::OPEN
            ]);
        });

        $this->fireModelEvent('assignAgent', false);
    }
    public function updateStatus($status){
        $this->update([
            'status'=>$status
        ]);
        $this->fireModelEvent('updateStatus', false);
    }

    public function Author(){
        return $this->belongsTo(User::class,'author_id');
    }

    public function Agent(){
        return $this->belongsTo(User::class,'agent_id');
    }

    public function Categories(){
        return $this->belongsTo(Categories::class,'category_id');
    }

    public function logable(){
        return $this->morphOne(logs::class,'logable');
    }

    public function labels(){
        return $this->belongsTo(labels::class,'label_id');
    }
}
