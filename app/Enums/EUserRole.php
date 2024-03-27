<?php

namespace App\Enums;

enum EUserRole: int {

    case ADMIN = 1;

    case MODERATOR = 2;

    case USER = 3;
}
