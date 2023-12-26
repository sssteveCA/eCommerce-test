<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use Exception;
use EcommerceTest\Interfaces\Constants as C;

/**
 * All logged user orders request
 */
class OrdersAll{

    public static function content(array $params): array{
        try{
            $dotenv = Dotenv::createImmutable(__DIR__."/../");
            $dotenv->load();
            $user = unserialize($params['session']['utente']);
            $username = $user->getUsername();
            $customer_orders = Ordine::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$_ENV['TABORD'],$_ENV['TABACC'],$username);
            $num_orders = count($customer_orders);
            if($num_orders > 0){
                $orders = [];
                foreach($customer_orders as $order){
                    $order = new Ordine(['id' => $order]);
                    if($order->getNumError() == 0){
                        $order_data = array(
                            'id' => $order->getId(),
                            'idc' =>$order->getIdc(),
                            'idp' => $order->getIdp(),
                            'idv' => $order->getIdv(),
                            'data' => $order->getData(),
                            'quantita' => $order->getQuantita(),
                            'totale' => $order->getTotale(),
                            'pagato' => ($order->isPagato())? '1':'0',
                            'carrello' => ($order->isCarrello())? '1':'0',
                        );
                        $orders[] = $order_data;
                    }//if($order->getNumError() == 0){
                    else
                        return [
                            C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_EMPTY => false, C::KEY_MESSAGE => $order->getStrError()
                        ];  
                }//foreach($customer_orders as $order){
                return [
                    C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_EMPTY => false, 'i' => $num_orders,C::KEY_MESSAGE => '',
                    'orders' => $orders, 'tab' => '1'
                ];  
            }//if(count($customer_orders) > 0){
            return [
                C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_EMPTY => true, C::KEY_MESSAGE => 'Nessun ordine effettuato'
            ];
        }catch(Exception $e){
            return [
                C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_EMPTY => false, C::KEY_MESSAGE => $e->getMessage()
            ];
        }
    }

}

?>