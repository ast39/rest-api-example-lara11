<?php

namespace App\Enums;

enum EOrderStatus: int {

    case CREATED = 1;

    case PROCESSED = 2;

    case PAID = 3;
    case DELIVERED = 4;
    case COMPLETED = 5;
    case CANCELED = 6;
}
