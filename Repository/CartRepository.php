<?php

namespace KivapiShop\Order\Repository;

use Core\Database\DB;
use Core\Database\Repository;
use DateTime;

class CartRepository extends Repository
{

    public function defaultTable(): string
    {
        return 'kshop_cart_item';
    }

    public function createCart(string $cartId)
    {
        try {
            DB::insert('kshop_cart', ['id' => $cartId, 'created_stamp' => new DateTime()]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                //already exists, do nothing
            } else {
                throw $e;
            }
        }
    }

    public function insertOrIncrement(array $data)
    {
        DB::beginTransaction();
        $this->createCart($data['cart_id']);
        try {
            DB::insert('kshop_cart_item', $data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                DB::query("UPDATE kshop_cart_item SET amount = amount + ? WHERE cart_id = ? AND product_type = ? AND product_id = ?", [
                    $data['amount'],
                    $data['cart_id'],
                    $data['product_type'],
                    $data['product_id']
                ]);
            } else {
                throw $e;
            }
        }
        DB::commit();
    }

    public function getItemsByCartId(string $cartId)
    {
        return DB::get("
SELECT ksci.* , kspv.name, kspv.price, kspv.price_currency
FROM kshop_cart_item ksci
LEFT JOIN kshop_base_product ksp ON ksp.id = ksci.product_id AND ksci.product_type = 'KivapiShop/BasicProduct'
LEFT JOIN kshop_base_product_version kspv ON kspv.id = ksp.current_version_id
WHERE cart_id = ?", [$cartId]);
    }

    public function getDeliveryDetails(string $cartId)
    {
        $ret= DB::get("SELECT ksc.id, ksc.delivery_details as deliveryDetails, (SELECT 1 FROM kshop_cart_item ksci WHERE ksci.cart_id = ksc.id LIMIT 1) as hasItems FROM kshop_cart ksc WHERE id = ?", [$cartId])[0] ?? null;
        if($ret){
            $ret->deliveryDetails = json_decode($ret->deliveryDetails);
        }
        return $ret;
    }

    public function updateDeliveryDetails(string $cartId, $deliveryDetails)
    {
        DB::update('kshop_cart', [
            'delivery_details' => json_encode($deliveryDetails)
        ], $cartId);
    }
}
