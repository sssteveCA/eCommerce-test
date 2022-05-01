<?php
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
require_once('navbar.php');
require_once('funzioni/functions.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ordini</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_ORDERS_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_DIALOG_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>></script>
        <script src=<?php echo P::REL_ORDERS_JS; ?>></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="ordiniT">
<?php
/*$ordini = getOrdini($_SESSION['user']);
if($ordini !== false){
    echo $ordini.'<br>';
}*/
?>
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>