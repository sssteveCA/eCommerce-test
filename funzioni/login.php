<?php

use EcommerceTest\Objects\Utente;

ob_start();
session_start();
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once('../objects/utente.php');
require_once('const.php');

//ob_start();
$_SESSION['logged'] = false;
if(isset($_POST['email'],$_POST['password']) && $_POST['email'] != '' && $_POST['password'] != ''){
    $dati = array();
    $dati['campo'] = 'email';
    $dati['email'] = $_POST['email'];
    $dati['password'] = $_POST['password'];
    $dati['registrato'] = '1';
    try{
        $utente = new Utente($dati);
        $err = $utente->getNumError();
        $login = $utente->isLogin();
        //accesso autorizzato
        if($err == 0 && $login){
            if($utente->getSesso() == 'Maschio') $_SESSION['welcome'] = 'Benvenuto '.$utente->getUsername();
            if($utente->getSesso() == 'Femmina') $_SESSION['welcome'] = 'Benvenuta '.$utente->getUsername();
            else $_SESSION['welcome'] = $utente->getUsername();
            $_SESSION['utente'] = serialize($utente);
            $_SESSION['logged'] = true;
            header('location: ../benvenuto.php');
        }
        else if($err == 1){
            echo 'Email o password non corretti<br>';
            header('refresh:7;url=../accedi.php');
        }
        else if($err == 2){
            echo 'Attiva l\'account per poter accedere<br>';
            header('refresh:10;url=../accedi.php');
        }
        else{
            echo 'Errore sconosciuto<br>';
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    } 
}//if(isset($_POST['email'],$_POST['password']) && $_POST['email'] != '' && $_POST['password'] != ''){
else{
    header('location: ../accedi.php');
}
?>