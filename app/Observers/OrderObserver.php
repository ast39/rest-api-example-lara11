<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderLog;


class OrderObserver {

    public function deleting(Order $order): void
    {
        OrderLog::query()->where('order_id', $order->id)
            ->delete();
    }
}
