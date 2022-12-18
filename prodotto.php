<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('partials/navbar.php');
require_once('interfaces/orderErrors.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
//require_once('interfaces/mysqlVals.php');
require_once('funzioni/config.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/prodotto.php');
require_once('objects/utente.php');
require_once('funzioni/const.php');
require_once('footer.php');
@include_once('partials/privacy.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
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
        <link rel="stylesheet" href=<?php echo P::REL_PRODUCT_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <!-- <script type="module" src=<?php //echo P::REL_DIALOG_MESSAGE_JS; ?>></script> -->
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script type="module" src=<?php echo P::REL_PRODUCT_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="container1" class="container">
            <!-- immagine del prodotto -->
            <div id="immagine">
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
        <label for="iQt" class="form-label me-2">Quantità</label>
        <input type="number" id="iQt" class="form-control" name="qt" value="1">
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
        <button type="submit" id="bCompra" class="btn btn-primary">COMPRA</button>
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
        <button type="submit" id="bElimina" class="btn btn-danger">ELIMINA</button>
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
                        <label for="oggetto" class="form-label me-2">Oggetto </label>
                        <input type="text" id="oggetto" class="form-control" name="pOggetto">
                    </div>
                    <div>
                        <label for="messaggio" class="form-label me-2">Messaggio</label>
                        <textarea id="messaggio" class="form-control" name="pMessaggio"></textarea>
                    </div>
                    <!--Indica il blocco di istruzioni che dovrà eseguire lo script php -->
                    <input type="hidden" name="oper" value="<?php echo '3'; ?>">
                    <!-- Destinatario mail -->
                    <input type="hidden" id="emailTo" name="emailTo" value="<?php echo $venditore->getEmail(); ?>">
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary">CONTATTA</button>
                        <div id="contacts-spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
<?php
    }
?>
        <?php echo footer(); ?>
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