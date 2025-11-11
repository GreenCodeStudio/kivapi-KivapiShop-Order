<?php

namespace KivapiShop\Order\Ajax;

use Core\Exceptions\NotFoundException;
use Core\Panel\Infrastructure\AjaxController;
use KivapiShop\Order\Cart;

class CartAjaxController extends AjaxController{
    public function addToCart(string $type, string $id, float $amount){
        if(empty($_COOKIE['kshop_cartId'] )){
            throw new NotFoundException();
        }
        $cart=new Cart($_COOKIE['kshop_cartId']);
        $cart->addToCart($type, $id, $amount);
    }
    public function updateDeliveryDetails($cartId, $deliveryDetails)
    {
        $cart=new Cart($cartId);
        $cart->updateDeliveryDetails($deliveryDetails);
    }
}
