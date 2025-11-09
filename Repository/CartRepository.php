<?php
namespace KivapiShop\Order\Repository;

use Core\Database\DB;
use Core\Database\Repository;

class CartRepository extends Repository
{

    public function defaultTable(): string
    {
        return 'kshop_cart_item';
    }

    public function insertOrIncrement(array $data)
    {
        DB::beginTransaction();
        try{
            $this->insert($data);
        }catch (\PDOException $e){
            if($e->getCode() == 23000){
                DB::query("UPDATE kshop_cart_item SET amount = amount + ? WHERE cart_id = ? AND product_type = ? AND product_id = ?", [
                    $data['amount'],
                    $data['cart_id'],
                    $data['product_type'],
                    $data['product_id']
                ]);
            }else{
                throw $e;
            }
        }
        DB::commit();
    }
}
