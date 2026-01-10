<?php

namespace KivapiShop\Order;

use Core\Database\DB;
use Core\Events\EventDispatcher;
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

        EventDispatcher::dispatch("KivapiShop.Order.AddToCart", (object)[
            'cart_id' => $this->cartId,
            'product_type' => $type,
            'product_id' => $id,
            'amount' => $amount,
            'add_stamp'=>new \DateTime()
        ]);
    }

    public function getItems()
    {
        return (new CartRepository())->getItemsByCartId($this->cartId);
    }

    public function getDeliveryDetails()
    {
        return (new CartRepository())->getDeliveryDetails($this->cartId);
    }

    public function updateDeliveryDetails($deliveryDetails)
    {
        return (new CartRepository())->updateDeliveryDetails($this->cartId, $deliveryDetails);
    }
}
