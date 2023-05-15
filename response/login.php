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
        $dotenv = Dotenv::createImmutable(__DIR__."/../");
        $dotenv->safeLoad();
        $dati = [
            'campo' => 'email', 'email' => $post['email'],'password' => $post['password'], 'registrato' => '1'
        ];
        try{
            $utente = new Utente($dati);
            $err = $utente->getNumError();
            $login = $utente->isLogin();
            if($err == 0 && $login){
                if($utente->getSesso() == 'Maschio') $_SESSION['welcome'] = 'Benvenuto '.$utente->getUsername();
                if($utente->getSesso() == 'Femmina') $_SESSION['welcome'] = 'Benvenuta '.$utente->getUsername();
                else $_SESSION['welcome'] = $utente->getUsername();
                $_SESSION['utente'] = serialize($utente);
                $_SESSION['logged'] = true;
                header('location: /');
                return [
                    C::KEY_DONE => true, C::KEY_MESSAGE => "", 'redirect' => ['do' => true, 'url' => '/']];
            }
            else if($err == 1){
                http_response_code(401);
                echo Msg::ERR_USERPWDWRONG.'<br>';
                return [
                    C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_USERPWDWRONG.'<br>', 
                    'redirect' => ['do' => true, 'url' => 'refresh:7;url=/']
                    ];
            }
            else if($err == 2){
                http_response_code(401);
                return [
                    C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_ACTIVEACCOUNT.'<br>', 
                    'redirect' => ['do' => true, 'url' => 'refresh:10;url=/']
                    ];
            }
            else{
                http_response_code(500);
                echo Msg::ERR_UNKNOWN.'<br>';
                return [
                    C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNKNOWN.'<br>', 'redirect' => ['do' => false, 'url' => '']];
            }
        }catch(Exception $e){
            http_response_code(500);
            return [
                C::KEY_DONE => false, C::KEY_MESSAGE => $e->getMessage(), 'redirect' => ['do' => false, 'url' => '']];
        }
    }
}
?>