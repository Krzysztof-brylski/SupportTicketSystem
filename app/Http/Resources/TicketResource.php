<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }


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

            'title'=>$this->title,
            'description'=>$this->description,
            'files'=>$this->files,
            'priority'=>$this->priority,
            'status'=>$this->status,

            'comments'=>$this->whenLoaded('Comments',function (){
                return CommentsResouce::collection($this->Comments);
            }),

            'category'=>$this->whenLoaded('Categories',function (){
                return $this->Categories->name;
            }),

            'label'=>$this->whenLoaded('Labels',function (){
                return $this->labels->name;
            }),

            'author'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return new UserResouce($this->Author);
            }),
            'agent'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return new UserResouce($this->Agent);
            }),

            'logs'=>$this->when($request->user()->tokenCan('role-admin'),function (){
                return $this->Logable;
            }),

       );
    }
}
