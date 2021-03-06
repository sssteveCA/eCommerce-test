<?php

use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\Messages as Msg;

require_once('../interfaces/messages.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('functions.php');
require_once('../objects/prodotto.php');
require_once('const.php');

$risposta = array();
if(isset($_POST['idU'],$_POST['nome'],$_POST['tipo'],$_POST['prezzo'],$_POST['spedizione'],$_POST['condizione'],$_POST['stato'],$_POST['citta'],$_POST['descrizione'])){
    if($_FILES['immagine']['error'] == 0){
        //l'immagine è in formato JPEG
        if(exif_imagetype($_FILES['immagine']['tmp_name']) == IMAGETYPE_JPEG){
            $dati = array();
            $dati['idU'] = $_POST['idU']; //ID dell'utente che ha caricato l'annuncio
            $dati['nome'] = $_POST['nome'];
            $dati['tipo'] = $_POST['tipo'];
            $dati['prezzo'] = $_POST['prezzo'];
            $dati['spedizione'] = $_POST['spedizione'];
            $dati['condizione'] = $_POST['condizione'];
            $dati['stato'] = $_POST['stato'];
            $dati['citta'] = $_POST['citta'];
            $dati['descrizione'] = $_POST['descrizione'];   
            $dati['tmp_name'] = $_FILES['immagine']['tmp_name']; 
            try{
                $prodotto = new Prodotto($dati);
                if($prodotto->getNumError() == 0){
                    $risposta['msg'] = Msg::INSERTIONUPLOADED;
                    $risposta['ok'] = '1';
                }
                else{
                    $risposta['msg'] = $prodotto->getStrError();
                }
            }
            catch(Exception $e){
                $risposta['msg'] = $e->getMessage();
            }
        }//if($_FILES['immagine']['error'] == 0){
        else{
            $risposta['msg'] = Msg::ERR_INSERTIONNOTIMAGE;
        }
    }
    else{
        $risposta['msg'] = Msg::ERR_INSERTIONFILENOTUPLOADED;
    }
}//if(isset($_POST['idU'],$_POST['nome'],$_POST['tipo'],$_POST['prezzo'],$_POST['spedizione'],$_POST['condizione'],$_POST['stato'],$_POST['citta'],$_POST['descrizione'])){
echo json_encode($risposta);
?>