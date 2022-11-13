<?php
session_start();

require_once('../config.php');
require_once("../interfaces/messages.php");
//require_once("../interfaces/mysqlVals.php");
require_once("../interfaces/productErrors.php");
require_once("../interfaces/productsVals.php");
require_once("../vendor/autoload.php");
require_once("../objects/prodotto.php");

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Messages as M;
use EcommerceTest\Objects\Prodotto;

$response = array(
    'done' => false,
    'msg' => ''
);
$input = file_get_contents('php://input');
$post = json_decode($input,true);
$response['post'] = $_POST;
$response['files'] = $_FILES;
$ajax = $_POST['ajax'];

//if an user is logged
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    if(isset($_POST['idU'],$_POST['name'],$_POST['type'],$_POST['price'],$_POST['shipping'],$_POST['condition'],$_POST['state'],$_POST['city'],$_POST['description'])){
        if($_FILES['image']['error'] == 0){
            $dotenv = Dotenv::createImmutable(__DIR__."/../");
            $dotenv->safeLoad();
            //Image file uploaded to the server
            create_insertion($_POST,$_FILES,$response);
        }//if($_FILES['image']['error'] == 0){
        else{
            http_response_code(400);
            $response['msg'] = M::ERR_INSERTIONFILENOTUPLOADED;
        }
            
    }//if(isset($_POST['idu'],$_POST['name'],$_POST['type'],$_POST['price'],$_POST['shipping'],$_POST['condition'],$_POST['state'],$_POST['city'],$_POST['description'])){
    else{
        http_response_code(400);
        $response['msg'] = M::ERR_REQUIREDFIELDSNOTFILLED;
    }
        
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{  
    http_response_code(401);
    if($ajax)$response['msg'] = M::ERR_NOTLOGGED;
    else echo M::ERR_LOGIN2;
}

if($ajax){
    echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}
else{
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Nuova inserzione</title>
        <meta charset="utf-8">
    </head>
    <body>
    {$response['msg']}
    </body>
</html>
HTML;
    echo $html;
}

//Insert data in DB
function create_insertion($post,$files,&$response): bool{
    $ok = false;
    if(exif_imagetype($files['image']['tmp_name']) == IMAGETYPE_JPEG){
        //MIME type is image/jpeg
        $data = [
            'idU' => $post['idU'],
            'nome' => $post['name'],
            'tipo' => $post['type'],
            'prezzo' => $post['price'],
            'spedizione' => $post['shipping'],
            'condizione' => $post['condition'],
            'stato' => $post['state'],
            'citta' => $post['city'],
            'descrizione' => $post['description'],
            'tmp_name' => $files['image']['tmp_name']
        ];
        try{
            $product = new Prodotto($data);
            $errno = $product->getNumError();
            if($errno == 0){
                //Insertion operation completed
                $response['msg'] = M::INSERTIONUPLOADED;
                $response['done'] = true;
                $ok = true;
            }//if($errno == 0){
            else{
                http_response_code(400);
                $response['msg'] = $product->getStrError();
            }
                
        }catch(Exception $e){
            http_response_code(500);
            $response['msg'] = $e->getMessage();
        }
    }//if(exif_imagetype($files['image']['tmp_name']) == IMAGETYPE_JPEG){
    else{
        http_response_code(400);
        $response['msg'] = M::ERR_INSERTIONNOTIMAGE;
    }
        
    return $ok;
}



?>