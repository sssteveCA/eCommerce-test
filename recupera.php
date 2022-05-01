<?php

use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');

if(isset($_SESSION['mail'],$_SESSION['user'],$_SESSION['logged']) && $_SESSION['mail'] != '' && $_SESSION['user'] != '' && $_SESSION['logged'] === true){
    header('location: benvenuto.php');
}
//per il recupero della password non ci devono essere sessioni aperte
else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_RECOVERY_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_DIALOG_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>></script>
        <script src=<?php echo P::REL_RECOVERY_JS; ?>></script>
    </head>
    <body>
        <div id="indietro">
            <a href="index.php"><img src="img/altre/indietro.png" alt="indietro" title="indietro"></a>
            <a href="index.php">Indietro</a>
        </div>
        <fieldset id="dRecupera">
            <legend>Recupera il tuo account</legend>
            <form id="fRecupera" method="post" action="funzioni/mail.php">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div>
                    <input type="submit" id="bOk" value="OK">
                </div>
            </form>
        </fieldset>
    </body>
</html>
<?php
}
?>