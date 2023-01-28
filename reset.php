<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente; 
use EcommerceTest\Interfaces\Paths as P;

ob_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('traits/sql.trait.php');
require_once('objects/emailmanager.php');
require_once('funzioni/functions.php');
require_once('objects/utente.php');
require_once('funzioni/config.php');
require_once('partials/footer.php');
@include_once('partials/privacy.php');

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recupera password</title>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <!-- <script type="module" src=<?php //echo P::REL_DIALOG_MESSAGE_JS; ?>></script> -->
        <script type="module" src=<?php echo P::REL_RESET_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    <head>
    <body>
<?php
$regex = '/^[a-z0-9]{64}$/i';
//se esiste il codice di recupero e se ha fatto il match con $regex
if(isset($_REQUEST['codReset']) && preg_match($regex,$_REQUEST['codReset'])){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $codReset = $_REQUEST['codReset'];
    $time = time()-$attesa;
    $dati = array();
    $utente = new Utente($dati);
    $esiste = $utente->Exists("`cambioPwd` = '$codReset' AND `dataCambioPwd` >= '$time'");
    //se il codice di cambio password esiste mostro il form
    if($esiste == 1){
?>
<fieldset id="f1">
    <legend>Recupero password</legend>
    <h2>Inserisci la nuova password</h2>
    <form action="funzioni/pRecovery.php" method="post" id="fRecupera">
        <div class="container">
            <div class="row my-5">
                <div class="col-12 col-md-5 col-lg-3">
                    <label for="nuova" class="form-label">Nuova password</label>
                </div>
                <div class="col-12 col-md-7 col-lg-9 mt-2 mt-md-0">
                    <input type="password" id="nuova" class="form-control" name="nuova">
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12 col-md-5 col-lg-3">
                    <label for="confNuova" class="form-label">Conferma nuova password</label>
                </div>
                <div class="col-12 col-md-7 col-lg-9 mt-2 mt-md-0">
                    <input type="password" id="confNuova" class="form-control" name="confNuova">
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12">
                    <input type="checkbox" id="showPass" class="form-check-input">
                    <label for="showPass" class="ms-2">Mostra password</label>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <button type="submit" id="conferma" class="btn btn-primary">CONFERMA</button>
                    <div id="spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="chiave" name="chiave" value="<?php echo $_REQUEST['codReset']; ?>">
    </form>
</fieldset>
<?php
    }
    else echo 'Codice non valido';
}
else echo 'Formato codice non corretto';
?>
        <?php echo footer(); ?>
    </body>
</html>