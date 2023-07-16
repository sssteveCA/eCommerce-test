<?php

namespace EcommerceTest\Response;

use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Objects\Utente;
use Exception;

/**
 * Reset account post request
 */
class ResetPost{

    public static function content(array $params): array{
        $post = $params['post'];
        $regex = '/^[a-z0-9]{64}$/i';
        if(isset($post['chiave']) && preg_match($regex,$post['chiave'])){
            if(isset($post['nuova'],$post['confNuova']) && $post['nuova'] != '' && $post['confNuova'] != ''){
                try{
                    if($post['nuova'] == $post['confNuova']){
                        $data = [
                            'campo' => 'cambioPwd',
                            'registrato' => '1',
                            'dimenticata' => '1',
                            'nuovaP' => $post['nuova'],
                            'cambioPwd' => $post['chiave'],
                            'dataCambioPwd' => time()-C::GENERATED_LINK_TIME
                        ];
                        $user = new Utente($data);
                        if($user->getNumError() == 0){
                            if($post[C::KEY_AJAX])
                                return [ C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => 'Password modificata' ];
                            else
                                return [ C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_HTML => self::nonAjaxRequest('Password modificata') ];
                        }
                        if($post[C::KEY_AJAX])
                            return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => $user->getStrError() ];
                        else
                            return [ C::KEY_CODE => 200, C::KEY_DONE => false, C::KEY_HTML => self::nonAjaxRequest($user->getStrError()) ];
                    }//if($post['nuova'] == $post['confNuova']){
                    if($post[C::KEY_AJAX])
                        return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_PWDNOTEQUAL];
                    else
                        return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_HTML => self::nonAjaxRequest(Msg::ERR_PWDNOTEQUAL) ];
                }catch(Exception $e){
                    if($post[C::KEY_AJAX])
                        return [ C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_PWDRESET ];
                    else
                        return [ C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_HTML => self::nonAjaxRequest(Msg::ERR_PWDRESET) ];
                }
            }//if(isset($post['nuova'],$post['confNuova']) && $post['nuova'] != '' && $post['confNuova'] != ''){
            if($post[C::KEY_AJAX])
                return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_PWDNOTSETTED ];
            else
                return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_HTML => self::nonAjaxRequest(Msg::ERR_PWDNOTSETTED) ];
        }//if(isset($post['chiave']) && preg_match($regex,$post['chiave'])){
        if($post[C::KEY_AJAX])
            return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_CODEINVALD ];
        else
            return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_HTML => self::nonAjaxRequest(Msg::ERR_CODEINVALD) ];
    }

    /**
     * HTML response for non AJAX requests
     * @param string $message
     * @return string
     */
    private static function nonAjaxRequest(string $message): string{
        return <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupero password</title>
        <meta charset="utf-8">
    </head>
    <body>
    {$message}
    </body>
</html>
HTML;
    }
}

?>