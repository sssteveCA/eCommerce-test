<?php

namespace EcommerceTest\Response;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as M;
use Exception;

class OrderDelete{

    public static function content(array $params): array{
        try{
            $dotenv = Dotenv::createImmutable(__DIR__."/../");
            $dotenv->load();
            $user = unserialize($params['session']['utente']);
            $order = new Ordine(array('id' => $_GET['idOrd']));
            $ok = $order->cancOrdine($user->getUsername());
            if($ok)
                return [
                    C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => M::ORDERDELETED,'aggiorna' => '1'
                ];
            throw new Exception;
        }catch(Exception $e){
            return [
                C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => M::ERR_ORDERDELETE
            ];
        }
    }
}
?>