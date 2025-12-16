<?php

namespace KivapiShop\Order;

use _PHPStan_5adafcbb8\Nette\Neon\Exception;
use _PHPStan_5adafcbb8\Nette\NotImplementedException;
use Core\Database\DB;
use KivapiShop\BasicProduct\Repository\ProductRepository;
use KivapiShop\Order\Repository\CartRepository;
use KivapiShop\Order\Repository\OrderRepository;
use MKrawczyk\FunQuery\FunQuery;

class Order
{
    public function __construct()
    {

    }

    public function insert($data)
    {
        $orderId = $this->uuidv4();
        $itemsByTypes = [];
        foreach ($data->items as $item) {
            $itemsByTypes[$item->product_type][] = $item;
        }
        $orderItems = [];
        foreach ($itemsByTypes as $productType => $items) {
            if ($productType == 'KivapiShop/BasicProduct') {
                $ids = FunQuery::create($items)->map(fn($item) => $item->product_id);
                $products = (new ProductRepository())->getForOrder($ids->toArray());
                foreach ($items as $item) {
                    $product = $products[$item->product_id];
                    if ($product->price != $item->price || $product->price_currency != $item->price_currency || $product->name != $item->name) {
                        throw new Exception();
                    }
                    $orderItems[] = [
                        'order_id' => $orderId,
                        'product_type' => 'KivapiShop/BasicProduct',
                        'product_id' => $product->id,
                        'amount' => $item->amount,
                        'price' => $product->price,
                        'name' => $product->name,

                    ];
                }
            } else {
                throw new NotImplementedException();
            }
        }
        (new OrderRepository())->insert(
            [
                'id' => $orderId,
                'source_cart_id' => $data->sourceCartId,
                'delivery_details' => json_encode($data->deliveryDetails),
                'invoice_details' => json_encode($data->invoiceDetails),
                'created_stamp' => new \DateTime(),
            ]
        );
        (new OrderRepository())->insertItems($orderItems);
    }

    function uuidv4()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
