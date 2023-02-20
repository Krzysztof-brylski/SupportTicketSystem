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


            'title'=>$this->title,
            'status'=>$this->status,
            'priority'=>$this->priority,

            'category'=>$this->whenLoaded('Categories',function (){
                return $this->Categories->name;
            }),
            'label'=>$this->whenLoaded('Labels',function (){
                return $this->labels->name;
            }),

            $this->mergeWhen($request->routeIs('ticket.show'),array(
                'description' => $this->description,
                'files' => $this->files,
                'comments'=>$this->whenLoaded('Comments',function (){
                    return CommentsResouce::collection($this->Comments);
                }),
            )),

            $this->mergeWhen($request->user()->tokenCan('role-admin'),array(

                'id'=>$this->id,
                'author'=>new UserResouce($this->Author),
                'agent'=>new UserResouce($this->Agent),
                'logs'=>$this->when($request->routeIs('ticket.show'),function (){
                    return $this->Logable;
                }),

            )),


       );
    }
}
