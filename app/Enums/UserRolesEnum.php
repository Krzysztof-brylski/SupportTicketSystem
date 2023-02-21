<?php


namespace App\Enums;


enum UserRolesEnum:string
{
    case ADMIN = "admin";
    case AGENT = "agent";
    case USER = "user";
}
