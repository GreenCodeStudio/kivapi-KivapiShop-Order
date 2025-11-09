<?php

namespace KivapiShop\BasicProduct\Components\Cart;

use Core\ComponentManager\ComponentController;
use Core\Exceptions\NotFoundException;
use KivapiShop\BasicProduct\Repository\ProductRepository;

class Controller extends ComponentController
{
    private mixed $id;
    private mixed $version;

    public function __construct($params)
    {
        parent::__construct();

    }

    public static function DefinedParameters()
    {
        return [
        ];
    }

    public function loadView()
    {
        include __DIR__.'/View.php';
    }
}
