<?php

namespace KivapiShop\Order\Components\OrderSummary;

use Core\ComponentManager\ComponentController;
use Core\Exceptions\NotFoundException;
use KivapiShop\BasicProduct\Repository\ProductRepository;
use KivapiShop\Order\Cart;

class Controller extends ComponentController
{


    public string $cartId;
    public object $deliveryDetails;
    public array $cartItems;
    public \Closure $formatCurrency;
    public string $orderInfo;

    public function __construct($params)
    {
        parent::__construct();
        $this->formatCurrency = function ($amount) {
            return number_format($amount / 100, 2, '.', '');
        };
        if (empty($_COOKIE['kshop_cartId'])) {
            throw new NotFoundException();
        } else {
            $cart = new Cart($_COOKIE['kshop_cartId']);
            $this->cartId = $_COOKIE['kshop_cartId'];
            $this->deliveryDetails = $cart->getDeliveryDetails();
            $this->cartItems = $cart->getItems();
            $this->orderInfo = json_encode(['items' => $this->cartItems, 'deliveryDetails' => $this->deliveryDetails, 'sourceCartId' => $this->cartId]);
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
