<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente; 
use EcommerceTest\Interfaces\Paths as P;

ob_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('interfaces/userErrors.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('funzioni/functions.php');
require_once('objects/utente.php');
require_once('funzioni/config.php');
require_once('footer.php');
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recupera password</title>
        <link rel="stylesheet" href=<?php echo P::REL_RESET_CSS; ?> type="text/css">
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script src=<?php echo P::REL_RESET_JS; ?>></script>
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
        <div>
            <label for="nuova" class="form-label">Nuova password</label>
            <input type="password" id="nuova" class="form-control" name="nuova">
        </div>
        <div>
            <label for="confNuova" class="form-label">Conferma nuova password</label>
            <input type="password" id="confNuova" class="form-control" name="confNuova">
        </div>
        <div>
            <input type="hidden" id="chiave" name="chiave" value="<?php echo $_REQUEST['codReset']; ?>">
            <button type="submit" id="conferma" class="btn btn-primary">CONFERMA</button>
        </div>
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