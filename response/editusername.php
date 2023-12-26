<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use Exception;

class EditUsername{

    public static function content(array $params): array{
        $post = $params['post'];
        $regex = '/(^$|^\s+$)/';
        if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $user = unserialize($params['session']['utente']);
                $data = [
                    'campo' => 'username',
                    'username' => $user->getUsername(),
                    'registrato' => true,
                ];
                $user = new Utente($data);
                $errno = $user->getNumError();
                if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
                    $data = ['username' => $post['username']];
                    $where = ['username' => $user->getUsername()];
                    $update = $user->update($data,$where);
                    if($update){
                        $_SESSION['welcome'] = '';
                        if($user->getSesso() == 'Maschio'){
                            $_SESSION['welcome'] = 'Benvenuto ';
                        }
                        else if($user->getSesso() == 'Femmina'){
                            $_SESSION['welcome'] = 'Benvenuta ';
                        }
                        $_SESSION['welcome'] .= $user->getUsername();
                        $response["user"] = $user->getUsername(); 
                        $_SESSION['utente'] = serialize($user);
                        return [C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::USERUPDATED
                        ];
                    }//if($update){
                    return [
                        C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_USERNOTUPDATED
                    ];
                }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
                return [
                    C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_USERNOTUPDATED
                ];
            }catch(Exception $e){
                return [
                    C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => $user->getStrError()
                ];
            }
        }//if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
        return [
            C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_REQUIREDFIELDSNOTFILLED
        ];
    }
}
?>