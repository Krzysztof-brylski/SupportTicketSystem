<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResouce extends JsonResource
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
            'id'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return $this->id;
            }),

            'content'=>$this->content,

            'auhtor'=>$this->whenLoaded('Author',function (){
                return new UserResouce($this->Auhtor);
            }),

        );
    }
}
