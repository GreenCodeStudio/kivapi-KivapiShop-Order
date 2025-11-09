<?php

namespace KivapiShop\Order;

use KivapiShop\Order\Repository\CartRepository;

class Cart
{
    public function __construct(private string $cartId)
    {

    }


    public function addToCart(string $type, string $id, float $amount)
    {
        (new CartRepository())->insertOrIncrement([
            'cart_id' => $this->cartId,
            'product_type' => $type,
            'product_id' => $id,
            'amount' => $amount,
            'add_stamp'=>new \DateTime()
        ]);
    }
}
