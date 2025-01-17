<?php

namespace App\Enums;

enum ActionEnum: string
{
    case DELETE = 'delete';
    case CREATE = 'create';
    case UPDATE = 'update';
}
