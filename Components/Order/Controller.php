<?php

namespace KivapiShop\Order\Components\Order;

use Core\ComponentManager\ComponentController;
use Core\Exceptions\NotFoundException;
use KivapiShop\BasicProduct\Repository\ProductRepository;
use KivapiShop\Order\Cart;

class Controller extends ComponentController
{


    public string $cartId;
    public object $deliveryDetails;

    public function __construct($params)
    {
        parent::__construct();
        if (empty($_COOKIE['kshop_cartId'])) {
            throw new NotFoundException();
        } else {
            $cart = new Cart($_COOKIE['kshop_cartId']);
            $this->cartId = $_COOKIE['kshop_cartId'];
            $this->deliveryDetails = $cart->getDeliveryDetails();
            if ($this->deliveryDetails->hasItems == 0) {
                throw new NotFoundException();
            }
        }
    }

    public static function DefinedParameters()
    {
        return [
            'cart_id' => (object)['type' => 'string', 'canFromQuery' => true],
        ];
    }

    public function loadView()
    {
        $this->loadMPTS(__DIR__.'/View.mpts');
    }

}
