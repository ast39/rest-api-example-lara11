<?php

namespace App\Enums;

enum EOrderStatus: int {

    // Создана
    case CREATED = 1;

    // В обработке
    case IN_PROCESSING = 2;

    // Ожидает оплаты
    case AWAITING_PAYMENT = 3;

    // В доставке
    case IN_DELIVERY = 4;

    // Выполнен
    case COMPLETED = 5;

    // Отмене
    case CANCELED = 6;
}
