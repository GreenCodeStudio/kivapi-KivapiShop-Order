<?php

namespace KivapiShop\Order\Repository;

use Core\Database\DB;
use Core\Database\Repository;
use DateTime;

class OrderRepository extends Repository
{

    public function defaultTable(): string
    {
        return 'kshop_order';
    }

    public function insertItems(array $orderItems)
    {
        DB::insertMultiple('kshop_order_item', $orderItems);
    }

}
