<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use Exception;

/**
 * Get info about single order
 */
class Order{

    public static function content(array $params): array{
        try{
            $dotenv = Dotenv::createImmutable(__DIR__."/../");
            $dotenv->load();
            $idOrd = $params['get']['idOrd'];
            if(isset($params['session']['ordini'][$idOrd])){
                $order = new Ordine(['id' => $idOrd['id']]);
                if($order->getNumError() == 0){
                    $product = new Prodotto(['id' => $order->getIdp()]);
                    if($product->getNumError() == 0){
                        $seller_args = [
                            'id' => $product->getId(),
                            'registrato' => '1',
                        ];
                        $seller = new Utente($seller_args);
                        $seller_error = $seller->getNumError();
                        if($seller_error == 0 || $seller_error == Ue::INCORRECTLOGINDATA){
                            return [
                                C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => '',
                                'order' => [
                                    'nomeP' => $product->getNome(),
                                    'tipo' => $product->getTipo(),
                                    'prezzo' => sprintf("%.2f",$product->getPrezzo()),
                                    'spedizione' => sprintf("%.2f",$product->getSpedizione()),
                                    'quantita' => $order->getQuantita(),
                                    'stato' => $product->getStato(),
                                    'citta' => $product->getCitta(),
                                    'totale' => sprintf("%.2f",$order->getTotale()),
                                    'nome' => $seller->getNome(),
                                    'cognome' => $seller->getCognome(),
                                    'nascita' => $seller->getNascita(),
                                    'indirizzo' => $seller->getIndirizzo(),
                                    'numero' => $seller->getNumero(),
                                    //'citta' => $seller->getCitta(),
                                    'cap' => $seller->getCap(),
                                    'email' => $seller->getEmail(),         
                                ]
                            ];
                        }//if($seller_error == 0 || $seller_error == Ue::INCORRECTLOGINDATA){
                        return [
                            C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => $seller->getStrError()
                        ];
                    }//if($product->getNumError() == 0){
                    return [
                        C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => $product->getStrError()
                    ];
                }//if($order->getNumError() == 0){
                return [
                    C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => $order->getStrError()
                ];
            }//if(isset($params['session']['ordini'][$id])){
            return [
                C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => "Fornisci l'id dell'ordine per continuare"
            ];
        }catch(Exception $e){
            return [
                C::KEY_CODE => 500, C::KEY_DONE => false,
                C::KEY_MESSAGE => $e->getMessage()
            ];
        }
    }
}
?>