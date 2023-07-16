<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;

class RecoveryPost{

    public static function content(array $params): array{
        $post = $params['post'];
        if(isset($post['email']) && $post['email'] != ''){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $data = [
                    'campo' => 'email',
                    'registrato' => '1',
                    'dimenticata' => '1',
                    'email' => $post['email']
                ];
                $user = new Utente($data);
                $values = [
                    'cambioPwd' => $user->getCambioPwd(),
                    'dataCambioPwd' => $user->getDataCambioPwd()
                ];
                $where = [
                    'email' => $user->getEmail()
                ];
                if($user->update($values,$where)){
                    $resetAddr = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/reset';
                    $resetAddrCode = $resetAddr.'?codReset='.$user->getCambioPwd();
                    $mailParts = self::getMailParts($_ENV,$resetAddrCode);
                    $send = $user->sendMail($user->getEmail(),'Recupero password',$mailParts['body'],$mailParts['headers'],"noreply@{$_ENV['HOSTNAME']}.lan");
                    if($send){
                        return [
                            C::KEY_CODE => 200,
                            C::KEY_MESSAGE => Msg::EMAILRECOVERY
                        ];
                    }
                    throw new Exception(Msg::ERR_EMAILSENDING1);
                }
                throw new Exception($user->getStrError());
            }catch(Exception $e){
                return [
                    C::KEY_CODE => 500,
                    C::KEY_MESSAGE => $e->getMessage()
                ];
            } 
        }//if(isset($post['email']) && $post['email'] != ''){
        return [
            C::KEY_CODE => 400,
            C::KEY_MESSAGE => Msg::ERR_EMAILINSERT
        ];
    }

    /**
     * Get the recovery mail header and body
     * @param array $env
     * @param string $resetAddrCode
     * @return array
     */
    private static function getMailParts(array $env,string $resetAddrCode): array{
        return [
            'headers' => <<<HEADER
From: Admin <noreply@{$env['HOSTNAME']}.lan>
Reply-to: noreply@{$env['HOSTNAME']}.lan
Content-type: text/html
MIME-Version: 1.0
HEADER,
            'body' => <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <style>
            body{
                display: flex;
                justify-content: center;
            }
            div#pagina{
                background-color: cyan;
                padding: 40px;
            }
            div#account{
                background-color: lime;
                padding: 20px;
            }
            p{
                margin: 10px;
            }
            p#messaggio{
                font-size: 20px;
                font-weight: bold;
                color: blue;
            }
        </style>
    </head>
    <body>
        <div id="pagina">
            <p id="messaggio">Gentile utente, fai click sul link sottostante per reimpostare la password</p>
            <div id="account">
                    <p id="link"><a href="{$resetAddrCode}">{$resetAddrCode}</a></p>                   
            </div>
        </div>
    </body>
</html>
HTML
        ];
    }

    /**
     * HTML response for non AJAX requests
     * @param string $message
     * @return string
     */
    public static function nonAjaxRequest(string $message): string{
        return <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Mail</title>
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