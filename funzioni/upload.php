<?php
session_start();

require_once("../interfaces/messages.php");

use EcommerceTest\Interfaces\Messages as M;

$response = array(
    'done' => true,
    'msg' => ''
);
$input = file_get_contents('php://input');
$post = json_decode($input,true);
$response['post'] = $_POST;
$response['files'] = $_FILES;
$ajax = $_POST['ajax'];

//if an user is logged
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{  
    if($ajax)$response['msg'] = M::ERR_NOTLOGGED;
    else '<a href="../index.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
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



?>