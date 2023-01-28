<?php

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('partials/navbar.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('traits/sql.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/utente.php');
require_once('objects/prodotto.php');
require_once('funzioni/config.php');
require_once("funzioni/const.php");
require_once('partials/footer.php');
@include_once('partials/privacy.php');

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    //echo "Collegato<br>";
    if(isset($_SESSION['prodotto'],$_SESSION['venditore'],$_POST['qt']) && is_numeric($_POST['qt'])){
        $cliente = unserialize($_SESSION['utente']);
        //var_dump($_SESSION['prodotto']);
        $prodotto=unserializeProduct($_SESSION['prodotto']);
        $qt = $_POST['qt'];
        $totale = $qt*($prodotto->getPrezzo()+$prodotto->getSpedizione());
        //venditore che ha caricato l'annuncio
        $venditore = unserialize($_SESSION['venditore']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Acquista prodotto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_BUY_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_BUY_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <form id="conferma" method="post" action="conferma.php">
            <div id="divConf">
                <fieldset id="f1">
                    <legend>Dati Acquirente</legend>
                    <p id="cNome">Nome : <?php echo $cliente->getNome(); ?></p>
                    <p id="cCognome">Cognome : <?php echo $cliente->getCognome(); ?></p>
                    <p id="cData">Nato il : <?php echo $cliente->getNascita(); ?></p>
                    <p id="cCitta">Residente a : <?php echo $cliente->getCitta(); ?></p>
                    <p id="cIndirizzo">Indirizzo : <?php echo $cliente->getIndirizzo().', '.$cliente->getNumero(); ?></p>
                    <p id="cCap">CAP : <?php echo $cliente->getCap(); ?></p>
                </fieldset>
                <fieldset id="f2">
                    <legend>Dati prodotto</legend>
                    <p id="pVend">Venditore: <?php echo $venditore->getUsername(); ?></p>
                    <p id="pNome">Nome : <?php echo $prodotto->getNome(); ?></p>
                    <p id="pCat">Categoria : <?php echo $prodotto->getTipo(); ?></p>
                    <p id="pPrezzo">Prezzo : <?php printf("%.2f€",$prodotto->getPrezzo()*$qt); ?></p>
                    <p id="pSped">Spese di spedizione : <?php printf("%.2f€",$prodotto->getSpedizione()*$qt); ?></p>
                    <p id="pQt">Quantità : <?php echo $qt; ?></p>
                    <p id="pVend">Spedito da : <?php echo $prodotto->getStato().', '.$prodotto->getCitta(); ?></p>
                    <p id="pTotale">Totale : <?php printf("%.2f€",$totale); ?></p>
                </fieldset>
                <div id="buttons">
                    <input type="hidden" id="idC" name="idC" value="<?php echo $cliente->getId(); ?>">
                    <input type="hidden" id="idP" name="idP" value="<?php echo $prodotto->getId(); ?>">
                    <input type="hidden" id="idV" name="idV" value="<?php echo $venditore->getId(); ?>">
                    <input type="hidden" id="nP" name="nP" value="<?php echo $qt; ?>">
                    <input type="hidden" id="tot" name="tot" value="<?php echo sprintf("%.2f€",$totale);; ?>">
                    <!-- <input type="hidden" id="qt" name="qt" value="<?php echo $qt; ?>"> -->
                    <button type="submit" id="bOk" class="btn btn-primary">CONFERMA</button>
                    <button type="button" id="bInd" class="btn btn-danger">INDIETRO</button>
                </div>
            </div>
        </form>
        <?php echo footer(); ?>
    </body>
</html>
<?php
    }
    else echo 'Seleziona il prodotto che vuoi acquistare per visualizzare questa pagina<br>';
}
else{
    echo ACCEDI1;
}
?>