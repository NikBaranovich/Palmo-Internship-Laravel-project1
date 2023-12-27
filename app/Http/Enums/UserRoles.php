<?php
namespace App\Http\Enums;

enum UserRoles: string
{
    case Admin = 'admin';
    case User = 'user';
}
