<?php

namespace EcommerceTest\Response;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use Exception;

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
                    $resetAddr = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/reset.php';
                    $resetAddrCode = $resetAddr.'?codReset='.$user->getCambioPwd();
                    $headers = <<<HEADER
From: Admin <noreply@{$_ENV['HOSTNAME']}.lan>
Reply-to: noreply@{$_ENV['HOSTNAME']}.lan
Content-type: text/html
MIME-Version: 1.0
HEADER;
                }
            }catch(Exception $e){

            }
            
        }//if(isset($post['email']) && $post['email'] != ''){
        return [];
    }

    private static function getMailParts(array $env,$resetAddrCode): array{
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
}

?>