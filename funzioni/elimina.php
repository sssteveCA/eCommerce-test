<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\Messages as Msg;

session_start();


require_once("../vendor/autoload.php");

use EcommerceTest\Interfaces\Constants as C;

$input = file_get_contents("php://input");
$post = json_decode($input,true);

$response = [ C::KEY_DONE => false, C::KEY_MESSAGE => '' ];

$ajax = (isset($post[C::KEY_AJAX]) && $post[C::KEY_AJAX] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    $id = $utente->getId();
    if(isset($post['idp']) && is_numeric($post['idp'])){
        $dati = array();
        $dati['id'] = $post['idp'];
        try{
            $prodotto = new Prodotto($dati);
            if($prodotto->getNumError() == 0){
                if($prodotto->cancella($id)){
                    $response[C::KEY_MESSAGE] = Msg::PRODDELETED;
                    $response[C::KEY_DONE] = true;
                }
                else{
                    http_response_code(500);
                    $response[C::KEY_MESSAGE] = Msg::ERR_PRODNOTDELETED;
                }
            }//if($prodotto->getNumError() == 0){
            else{
                http_response_code(500);
                $response[C::KEY_MESSAGE] = $prodotto->getStrError();
            }
        }
        catch(Exception $e){
            http_response_code(500);
            $response[C::KEY_MESSAGE] = $e->getMessage();
        }
    }//if(isset($post['idp']) && is_numeric($post['idp'])){
    else{
        http_response_code(400);
        $response[C::KEY_MESSAGE] = Msg::ERR_PRODINVALID;
    }
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    http_response_code(401);
    $response[C::KEY_MESSAGE] = Msg::ERR_NOTLOGGED;
}
if($ajax)echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
else{
    echo htmlResponse($response[C::KEY_MESSAGE]);
}

function htmlResponse(string $message): string{
    return <<<HTML
<!DOCTYPE html>
<html lang="IT">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cancellazione prodotto</title>
        <style>
            div#indietro{
                position: absolute;
                top: 30px;
                left: 30px;
                display: flex;
                align-items: center;
            }
            img{
                width: 60px;
                height: 60px;
            }
        </style>
    </head>
    <body>
        <div id="indietro">
            <a href="../inserzioni.php"><img src="../img/altre/indietro.png" alt="indietro" title="indietro"></a>
            <a href="../inserzioni.php">Indietro</a>
        </div>
        {$message}
    </body>
</html>
HTML;
}
?>