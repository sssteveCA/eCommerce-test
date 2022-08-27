<?php
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
require_once("funzioni/const.php");
require_once('navbar.php');


if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Benvenuto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_WELCOME_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_DIALOG_MESSAGE_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_WELCOME_JS; ?>></script>
    </head>
    <body>
    
        <?php echo menu($_SESSION['welcome']);?>
        <div id="search" class="d-flex flex-column flex-sm-row flex-grow-1">
            <form id="fSearch" class="flex-fill d-flex flex-column flex-sm-row justify-content-center justify-content-sm-start align-items-center" method="get" action="ricerca.php">
                <input type="text" id="ricerca" name="ricerca">
                <input type="submit" id="submit" class="btn btn-primary" value="RICERCA">
            </form>
            <p id="rAvanzata" class="flex-fill d-flex justify-content-center"><a href="avanzata.php">Ricerca avanzata</a></p>
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>