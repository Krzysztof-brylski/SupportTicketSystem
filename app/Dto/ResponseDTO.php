<?php


namespace App\Dto;


class ResponseDTO
{
    public $data;
    public $message;
    public $error;

    public function __construct($data, $message, $error)
    {
        $this->data=$data;
        $this->message=$message;
        $this->error=$error;
    }

    public function toArray(){
        return array(
            "data" => $this->data,
            "message" => $this->message,
            "error"=>$this->error,
        );
    }

}
