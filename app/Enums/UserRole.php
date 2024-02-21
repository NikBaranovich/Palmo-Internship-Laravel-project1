<?php

namespace App\Enums;

use App\Traits\EnumArrayable;

enum UserRole: string
{
    use EnumArrayable;

    case Admin = 'admin';
    case User = 'user';
    case Superadmin = 'superadmin';
}
