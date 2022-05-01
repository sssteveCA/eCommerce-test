<?php

use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;

session_start();

require_once('interfaces/orderErrors.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/mysqlVals.php');
require_once('funzioni/config.php');
require_once('objects/prodotto.php');
require_once('objects/utente.php');
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $dati = array();
        $dati['id'] = $_GET['id'];
        try{
            $prodotto = new Prodotto($dati);
            $_SESSION['prodotto'] = serialize($prodotto);
            //oggetto Utente del venditore che ha caricato l'annuncio
            $seller = array();
            $seller['id'] = $prodotto->getIdu();
            $seller['registrato'] = '1';
            $seller['password'] = '123456';
            $venditore = new Utente($seller);
            $_SESSION['venditore'] = serialize($venditore);
            //echo '<script>console.log("'.unserialize($_SESSION['prodotto']).'");</script>';
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <!-- Nome del prodotto -->
        <title><?php echo $prodotto->getNome(); ?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/utente.css" type="text/css">
        <link rel="stylesheet" href="css/prodotto.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/prodotto.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="menu">
                <div id="welcome"><?php echo $_SESSION['welcome']; ?></div>
                <div id="profilo">
                    Profilo
                    <div>
                        <a href="info.php">Informazioni</a>
                        <a href="edit.php">Modifica</a>
                    </div>
                </div>
                <div id="ordini">
                    Ordini
                    <div>
                        <a href="ordini.php">I miei ordini</a>
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>
                <div id="prodotto">
                    Prodotto
                    <div>
                        <a href="benvenuto.php">Cerca</a>
                        <a href="crea.php">Crea inserzione</a>
                        <a href="inserzioni.php">Le mie inserzioni</a>
                    </div>
                </div>
                <div id="contatti"><a href="contatti.php">Contatti</a></div>
                <div id="logout"><a href="funzioni/logout.php">Esci</a></div>
            </div>
        </div>
        <div id="container1" class="container">
            <!-- immagine del prodotto -->
            <div id="immagine" >
            <?php echo '<img src="'.$prodotto->getImmagine().'">'; ?>
            </div>
            <div id="dInfo1">
                <!-- nome completo del prodotto in grassetto -->
                <fieldset id="info1">
                    <?php echo '<b>'.$prodotto->getNome().'</b>'; ?>
                </fieldset>
                <fieldset id="info2">
<?php
    $formHTML = '';
    $prezzo = sprintf("%.2f EUR",$prodotto->getPrezzo());
    $spese = sprintf("%.2f EUR",$prodotto->getSpedizione());
    //l'utente può comprare il prodotto se non è lo stesso che lo ha caricato
    if($utente->getId() != $prodotto->getIdu()){
        $formHTML = <<<HTML
<form id="compra" method="post" action="compra.php">
    <div id="tipo" class="info">
        <!-- Categoria del prodotto -->
        Tipo: {$prodotto->getTipo()} 
    </div>
    <div id="condizione">
        <!-- Condizione prodotto: nuovo,usato o non specificato -->
        Condizione : {$prodotto->getCondizione()} 
    </div>
    <div id="qt" class="info">
        <!-- numero di prodotti che l'utente vuole comprare -->
        <label for="iQt">Quantità</label>
        <input type="number" id="iQt" name="qt" value="1">
    </div>
    <div id="prezzo" class="info">
        <!-- Prezzo in euro -->
        Prezzo: {$prezzo}
    </div>
    <div id="spedizione" class="info">
        <!-- Spese di spedizione in euro -->
        Spese di spedizione: {$spese} 
    </div>
    <div id="dCompra" class="info">
        <input type="hidden" id="idp" name ="idp" value="{$prodotto->getId()}">
        <input type="submit" id="bCompra" value="COMPRA">
        </div>
</form>
HTML;
    }
    else{
        $formHTML = <<<HTML
<form id="elimina" method="post" action="funzioni/elimina.php">
    <div id="tipo" class="info">
        <!-- Categoria del prodotto -->
        Tipo: {$prodotto->getTipo()} 
    </div>
    <div id="condizione">
        <!-- Condizione prodotto: nuovo,usato o non specificato -->
        Condizione : {$prodotto->getCondizione()} 
    </div>
    <div id="prezzo" class="info">
        <!-- Prezzo in euro -->
        Prezzo: {$prezzo}
    </div>
    <div id="spedizione" class="info">
        <!-- Spese di spedizione in euro -->
        Spese di spedizione: {$spese} 
    </div>
    <div id="dCompra" class="info">
        <input type="hidden" id="idp" name ="idp" value="{$prodotto->getId()}">
        <input type="submit" id="bElimina" value="ELIMINA">
    </div>
</form>
HTML;
    } 
    echo $formHTML;
?>
                </fieldset>
            </div>
        </div>
        <div id="dInfo2" class="container">
            <fieldset id="info3">
                    <div id="seller" class="info">
                        Venditore: <?php echo $venditore->getUsername(); ?>
                    </div>
                    <div id="data" class="info">
                        Data Inserzione: <?php echo $prodotto->getData(); ?>
                    </div>
                    <div id="stato" class="info">
                        Stato provenienza: <?php echo $prodotto->getStato(); ?>
                    </div>
                    <div id="luogo" class="info">
                        Luogo di provenienza: <?php echo $prodotto->getCitta(); ?>
                    </div>
            </fieldset>
        </div>
        <div id="descrizione" class="container">
            <h1>Descrizione prodotto</h1>
            <div>
                <fieldset id="info4">
                    <!-- Descrizione del prodotto. La funzione nl2br converte i '\n' in <br> -->
                    <?php echo nl2br($prodotto->getDescrizione()); ?>
                </fieldset>
            </div>
        </div>
<?php
    if($utente->getId() != $prodotto->getIdu()){
?>
        <div id="email" class="container">
            <fieldset id="fEmail">
                <legend>Contatta il venditore</legend>
                <form id="formMail" method="post" action="funzioni/mail.php">
                    <div>
                        <label for="oggetto">Oggetto </label>
                        <input type="text" id="oggetto" name="pOggetto">
                    </div>
                    <div>
                        <label for="messaggio">Messaggio</label>
                        <textarea id="messaggio" name="pMessaggio"></textarea>
                    </div>
                    <!--Indica il blocco di istruzioni che dovrà eseguire lo script php -->
                    <input type="hidden" name="oper" value="<?php echo '3'; ?>">
                    <!-- Destinatario mail -->
                    <input type="hidden" id="emailTo" name="emailTo" value="<?php echo $venditore->getEmail(); ?>">
                    <input type="submit" value="CONTATTA">
                </form>
            </fieldset>
        </div>
<?php
    }
?>
    </body>
</html>
<?php 
        }
        catch(Exception $e){
            echo $e->getMessage().'<br>';
            echo ' Linea n. '.__LINE__;
        }
    }
    else echo 'Prodotto specificato non valido<br>';
}
else{
    echo ACCEDI1;
}
?>