<?php

namespace EcommerceTest\Response;

use EcommerceTest\Interfaces\Constants as C;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\SuccessParent;

/**
 * Payment success AJAX response
 */
class SuccessPost extends SuccessParent{

    public static function content(array $params): array{
        $response = [
            C::KEY_DONE => false,
            C::KEY_MESSAGE => ''
        ];
        $post = $params['post'];
        $session = $params['session'];
        try{
            $dotenv = Dotenv::createImmutable(__DIR__."/../");
            $dotenv->load();
            $user = unserialize($session['utente']);
            if($_POST["payer_status"] == 'VERIFIED'){
                if(isset($session['ido'])){
                    $data = [
                        'id' => $session['ido']
                    ];
                    $order = new Ordine($dati);
                    //l'ordine era presente nel carrello
                    if($order->isCarrello() === true){
                        $del = $order->delFromCart($user->getUsername());
                        if($del){
                            $values = [
                                'tnx_id' => $post['tnx_id'], 
                                'pagato' => '1'
                            ];
                            $order->update($values);
                            if($order->getNumError() == 0){
                                $response = [
                                    C::KEY_DONE => true,
                                    C::KEY_MESSAGE => 'Pagamento effettuato con successo'
                                ];
                            }
                            else{
                                $response[C::KEY_CODE] = 500;
                                $response[C::KEY_MESSAGE] = $ordine->getStrError();
                            }
                        }
                        else{
                            $response[C::KEY_CODE] = 500;
                            $response[C::KEY_MESSAGE] = $ordine->getStrError();
                        }
                    }
                    else{
                        $response[C::KEY_CODE] = 400;
                        $response[C::KEY_MESSAGE] = 'Aggiungi al carrello il prodotto e riprova';
                    }
                }
                else{
                    $response[C::KEY_CODE] = 404;
                    $response[C::KEY_MESSAGE] = 'Id ordine inesistente';
                }
            }
        }catch(Exception $e){
            $response[C::KEY_CODE] = 500;
            $response[C::KEY_MESSAGE] = $e->getMessage();
        }
        return $response;
    }

}

?>