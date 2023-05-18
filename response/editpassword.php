<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;
use Exception;

class EditPassword{

    public static function content(array $params): array{
        $post = $params['post'];
        $regex = '/(^$|^\s+$)/';
        if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])
            && !preg_match($regex,$post["oPwd"]) 
            && !preg_match($regex,$post["nPwd"])
            && !preg_match($regex,$post["confPwd"]) && $post["pwd"] == "1"){
                if($post["nPwd"] == $post["confPwd"]){
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
                            $passwordC = $user->getPassword();
                            if(password_verify($post["nPwd"],$passwordC)){
                                $new = [ 'password' => $post['nPwd']];
                                $where = ['username' => $user->getUsername()];
                                $update = $user->update($new,$where);
                                if($update){
                                    return [
                                        C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::PWDUPDATED
                                    ];
                                }
                                return [
                                    C::KEY_CODE => 500, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_PWDNOTUPDATED
                                ];
                            }//if(password_verify($post["nPwd"],$passwordC)){
                            return [
                                C::KEY_CODE => 401, C::KEY_DONE => true, 
                                C::KEY_MESSAGE => Msg::ERR_PWDCURRENTWRONG
                            ];
                        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
                        return [
                            C::KEY_CODE => 401, C::KEY_DONE => true, 
                            C::KEY_MESSAGE => $user->getStrError()
                        ];
                    }catch(Exception $e){
                        return [
                            C::KEY_CODE => 500, C::KEY_DONE => true, 
                            C::KEY_MESSAGE => Msg::ERR_PWDNOTUPDATED
                        ];
                    }
                }//if($post["nPwd"] == $post["confPwd"]){
                return [
                    C::KEY_CODE => 400, C::KEY_DONE => false, 
                    C::KEY_MESSAGE => Msg::ERR_PWDCONFDIFFERENT];
        }// if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])...
        return [
            C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_REQUIREDFIELDSNOTFILLED
        ];
    }
}
?>