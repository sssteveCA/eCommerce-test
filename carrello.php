<?php

use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
/* require_once('objects/utente.php');
require_once('objects/prodotto.php');
require_once('objects/ordine.php'); */
require_once('partials/navbar.php');
require_once("funzioni/const.php");
require_once('partials/footer.php');
@include_once('partials/privacy.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Carrello</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_CART_CSS; ?>>
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
        <script type="module" src=<?php echo P::REL_CART_JS; ?>></script>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="carrello">
<?php
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