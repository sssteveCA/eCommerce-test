<?php

use EcommerceTest\Objects\Utente; 

ob_start();

require_once('interfaces/userErrors.php');
require_once('interfaces/mysqlVals.php');
require_once('funzioni/functions.php');
require_once('objects/utente.php');
require_once('funzioni/config.php');
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recupera password</title>
        <link rel="stylesheet" href="css/reset.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/reset.js"></script>
    <head>
    <body>
<?php
$regex = '/^[a-z0-9]{64}$/i';
//se esiste il codice di recupero e se ha fatto il match con $regex
if(isset($_REQUEST['codReset']) && preg_match($regex,$_REQUEST['codReset'])){
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
            <label for="nuova">Nuova password</label>
            <input type="password" id="nuova" name="nuova">
        </div>
        <div>
            <label for="confNuova">Conferma nuova password</label>
            <input type="password" id="confNuova" name="confNuova">
        </div>
        <div>
            <input type="hidden" id="chiave" name="chiave" value="<?php echo $_REQUEST['codReset']; ?>">
            <input type="submit" id="conferma" value="CONFERMA">
        </div>
    </form>
</fieldset>
<?php
    }
    else echo 'Codice non valido';
}
else echo 'Formato codice non corretto';
?>
    </body>
</html>