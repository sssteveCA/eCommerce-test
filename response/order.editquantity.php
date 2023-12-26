<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as M;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use Exception;

class OrderEditQuantity{

    public static function content(array $params): array{
        $put = $params['put'];
        $id_ok = (isset($put['id']) && $put['id'] != '');
        $quantity_ok = (isset($put['quantity']) && is_numeric($put['quantity']) && $put['quantity'] > 0);
        if($id_ok && $quantity_ok){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $order = new Ordine(array('id' => $put['id']));
                if($order->getNumError() == 0){
                    $idp = $order->getIdp();
                    $product = new Prodotto(array('id' => $idp));
                    if($product->getNumError() == 0){
                        $price = $product->getPrezzo();
                        $shipping = $product->getSpedizione();
                        $quantity_array = [
                            'quantita' => $put['quantity'],
                            'totale' => sprintf("%.2f",($put['quantity']*($price+$shipping)))
                        ];
                        if($order->update($quantity_array))
                            return [
                                C::KEY_CODE => 200,
                                C::KEY_DONE => true,
                                C::KEY_MESSAGE => M::ORDERAMOUNTUPDATED,
                                'aggiorna' => '1'
                            ];
                        throw new Exception;
                    }//if($product->getNumError() == 0){
                    return [
                        C::KEY_CODE => 400,
                        C::KEY_DONE => false,
                        C::KEY_MESSAGE => $product->getStrError()
                    ];
                }//if($order->getNumError() == 0){
                return [
                    C::KEY_CODE => 400,
                    C::KEY_DONE => false,
                    C::KEY_MESSAGE => $order->getStrError()
                ];
            }catch(Exception $e){
                return [
                    C::KEY_CODE => 500,
                    C::KEY_DONE => false,
                    C::KEY_MESSAGE => M::ERR_ORDERAMOUNTNOTPDATED
                ];
            }
        }//if($id_ok && $quantity_ok){
        return [
            C::KEY_CODE => 400,
            C::KEY_DONE => false,
            C::KEY_MESSAGE => M::ERR_ORDERINVALID
        ];
    }
}
?>