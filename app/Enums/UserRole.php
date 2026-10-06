<?php

namespace App\Enums;

enum UserRole: string
{
    case Client = 'cliente';
    case Administrator = 'administrador';
}
