<?php

use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\Messages as Msg;

session_start();

require_once('../interfaces/messages.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/productsVals.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once('../objects/prodotto.php');
//require_once('../objects/utente.php');

$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $risposta = array();
    $utente = unserialize($_SESSION['utente']);
    $id = $utente->getId();
    if(isset($_POST['idp']) && is_numeric($_POST['idp'])){
        $dati = array();
        $dati['id'] = $_POST['idp'];
        try{
            $prodotto = new Prodotto($dati);
            if($prodotto->getNumError() == 0){
                if($prodotto->cancella($id)){
                    $risposta['msg'] = Msg::PRODDELETED;
                    $risposta['ok'] = '1';
                }
                else{
                    $risposta['msg'] = Msg::ERR_PRODNOTDELETED;
                }
            }//if($prodotto->getNumError() == 0){
            else{
                $risposta['msg'] = $prodotto->getStrError().'<br>';
                $risposta['msg'] .= ' Linea n. '.__LINE__;
            }
        }
        catch(Exception $e){
            $risposta['msg'] = $e->getMessage().'<br>';
            $risposta['msg'] .= ' Linea n. '.__LINE__;
        }
    }//if(isset($_POST['idp']) && is_numeric($_POST['idp'])){
    else{
        $risposta['msg'] = Msg::ERR_PRODINVALID;
    }
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    $risposta['msg'] = Msg::ERR_NOTLOGGED;
}
if($ajax)json_encode($risposta);
else{
?>
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
    <?php echo $risposta['msg']; ?>
</body>
</html>
<?php
}
?>