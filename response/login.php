<?php

namespace EcommerceTest\Response;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use Exception;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;

/**
 * Login POST request
 */
class Login{
    public static function content(array $params): array{
        $post = $params['post'];
        if(isset($post['email'],$post['password']) && $post['email'] != '' && $post['password'] != ''){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $dati = [
                    'campo' => 'email', 'email' => $post['email'],'password' => $post['password'], 'registrato' => '1'
                ];
                $utente = new Utente($dati);
                $err = $utente->getNumError();
                $login = $utente->isLogin();
                if($err == 0 && $login){
                    if($utente->getSesso() == 'Maschio') $_SESSION['welcome'] = 'Benvenuto '.$utente->getUsername();
                    if($utente->getSesso() == 'Femmina') $_SESSION['welcome'] = 'Benvenuta '.$utente->getUsername();
                    else $_SESSION['welcome'] = $utente->getUsername();
                    $_SESSION['utente'] = serialize($utente);
                    $_SESSION['logged'] = true;
                    return [
                        C::KEY_CODE => 200,C::KEY_DONE => true, C::KEY_MESSAGE => "", 'redirect' => ['do' => true, 'url' => 'Location: /']];
                }
                else if($err == 1){
                    return [
                        C::KEY_CODE => 401, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_USERPWDWRONG.'<br>', 
                        'redirect' => ['do' => true, 'url' => 'refresh:7;url=/']
                        ];
                }
                else if($err == 2){
                    return [
                        C::KEY_CODE => 401, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_ACTIVEACCOUNT.'<br>', 
                        'redirect' => ['do' => true, 'url' => 'refresh:10;url=/']
                        ];
                }
                return [
                    C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNKNOWN.'<br>', 'redirect' => ['do' => false, 'url' => '']];
                
            }catch(Exception $e){
                return [
                    C::KEY_CODE => 500,C::KEY_DONE => false, C::KEY_MESSAGE => $e->getMessage(), 'redirect' => ['do' => false, 'url' => '']];
            }
        }//if(isset($post['email'],$post['password']) && $post['email'] != '' && $post['password'] != ''){
        return [
            C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_REQUIREDFIELDSNOTFILLED, 'redirect' => ['do' => false, 'url' => '']
        ];
    }
}
?>