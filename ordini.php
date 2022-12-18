<?php
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
require_once('partials/navbar.php');
require_once('funzioni/functions.php');
require_once("funzioni/const.php");
require_once("footer.php");
@include_once('partials/privacy.php');


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
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src="js/dialog/dialog.js"></script> <!-- temporary -->
        <script type="module" src=<?php echo P::REL_ORDERS_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
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
        <?php echo footer(); ?>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>