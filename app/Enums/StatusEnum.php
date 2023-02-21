<?php


namespace App\Enums;


enum StatusEnum:string
{
    case AWAITING="awaiting";
    case OPEN="open";
    case CLOSED="closed";
}
