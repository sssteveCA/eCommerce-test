<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as M;
use EcommerceTest\Objects\Ordine;
use Exception;

class OrderAddToCart{

    public static function content(array $params): array{
        if(isset($params['put']['id']) && $params['put']['id'] != ''){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $user = unserialize($params['session']['utente']);
                $order = new Ordine(array('id' => $params['put']['id']));
                if($order->getNumError() == 0){
                    if(!$order->isCarrello()){
                        $add = $order->addToCart($user->getUsername());
                        if($add)
                            return [
                                C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => M::ORDERINSERTEDCART,
                                'aggiorna' => '1'
                            ];
                        throw new Exception;
                    }//if(!$order->isCarrello()){
                    return [
                        C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => M::ERR_ORDERALREALDYCART,
                    ];
                }//if($order->getNumError() == 0){
                throw new Exception;
            }catch(Exception $e){
                return [
                    C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => M::ERR_ORDERADDTOCART,
                ];
            }
        }//if(isset($params['put']['id']) && $params['put']['id'] != ''){
        return [
            C::KEY_CODE => 404, C::KEY_DONE => false, C::KEY_MESSAGE => M::ERR_ORDERINVALID
        ];
    }
}
?>