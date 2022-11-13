<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;

require_once('config.php');
//require_once('interfaces/mysqlVals.php');
require_once('interfaces/userErrors.php');
require_once('vendor/autoload.php');
require_once('objects/utente.php');

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attivazione account</title>
    <style>
        body{
            display: flex;
            justify-content: center;
        }
        fieldset div{
            margin: 10px;
        }
        fieldset div:last-child{
            display: flex;
            justify-content: center;
        }
        fieldset div > *{
            margin-right: 5px;
        }
        input#codAut{
            width: 300px;
        }
        input#attiva{
            padding: 5px;
        }
    </style>
</head>
<body>
 
<?php
    $regex = '/^[a-z0-9]{64}$/i';
    if(isset($_REQUEST['codAut']) && preg_match($regex,$_REQUEST['codAut'])){
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
        $dati = array();
        $dati['campo'] = 'codAut';
        $dati['codAut'] = $_REQUEST['codAut'];
        $dati['registrato'] = '1';
        try{
            $utente = new Utente($dati);
            $codAut = $utente->getCodAut();
            $error = $utente->getNumError();
            //account attivato
            if(!isset($codAut) && $error === 0){
                echo 'L\' account è stato attivato';
            }
            //account non attivato
            else{
                echo 'Impossibile attivare l\'account<br>';
                //echo "Errore n. {$error}";
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }//if(isset($_REQUEST['codAut']) && preg_match($regex,$_REQUEST['codAut'])){
    else{
?>
<fieldset id="f1">
    <legend>Attivazione account</legend>
    <h2>Inserisci il codice di attivazione</h2>
    <form action="attiva.php" method="post" id="fAttiva">
        <div>
            <label for="codAut">Codice</label>
            <input type="text" id="codAut" name="codAut">
        </div>
        <div>
            <input type="submit" id="attiva" value="ATTIVA">
        </div>
    </form>
</fieldset>
<?php
    }
?>
   
   </body>
</html>