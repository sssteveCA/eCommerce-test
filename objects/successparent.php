<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Objects\Ordine;
use EcommerceTest\Interfaces\Constants as C;

/**
 * Class used by success page and ajax response classes
 */
abstract class SuccessParent{

    protected static function successResponse(array $params): array{
        $response = [];
        $post = $params['post'];
        $session = $params['session'];
        if($post["payer_status"] == 'VERIFIED'){
            if(isset($session['ido'])){
                $dati = array();
                $dati['id'] = $session['ido'];
                try{
                    $ordine = new Ordine($dati);
                    //l'ordine era presente nel carrello
                    if($ordine->isCarrello() === true){
                        $del = $ordine->delFromCart($utente->getUsername());
                        if($del){
                            $values = [
                                'tnx_id' => $post['tnx_id'], 'pagato' => '1'
                            ];
                            $ordine->update($valori);
                            if($ordine->getNumError() == 0){
                                $response[C::KEY_MESSAGE] = 'Pagamento effettuato con successo';
                            }
                            else{
                                $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                            } 
                        }
                        else{
                            $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                            $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                        }
                    }//if($ordine->isCarrello() === true){
                    else{
                        $response[C::KEY_MESSAGE] = 'Aggiungi al carrello il prodotto e riprova';
                    }
                }
                catch(Exception $e){
                    $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
                    $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                }
            }
            else{
                $response[C::KEY_MESSAGE] = 'Id ordine inesistente';
            } 
        }//if($post["payer_status"] == 'VERIFIED'){
        return $response;
    }

}
?>