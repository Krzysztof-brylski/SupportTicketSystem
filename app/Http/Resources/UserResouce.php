<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array(

            'name'=>$this->name,
            'role'=>$this->role,

            'id'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return $this->id;
            }),
            'email'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return $this->email;
            }),
            'created_at'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return $this->created_at;
            }),
        );
    }
}
