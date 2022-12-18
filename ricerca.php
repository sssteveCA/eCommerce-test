<?php

use Dotenv\Dotenv;
use EcommerceTest\Exceptions\InvalidValueException;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Objects\AdvancedSearch;
use EcommerceTest\Interfaces\Messages as M;

session_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('partials/navbar.php');
require_once('footer.php');
require_once('exceptions/nouserinstance.php');
require_once('exceptions/incorrectuserinstanceformat.php');
require_once('exceptions/invalidvalue.php');
require_once('interfaces/messages.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('objects/emailmanager.php');
require_once('traits/error.php');
require_once('traits/searchquerybuilder.php');
require_once('traits/searchtable.php');
require_once('objects/prodotto.php');
require_once('objects/utente.php');
require_once('objects/advancedsearch.php');
require_once('funzioni/config.php');
require_once('funzioni/paypalConfig.php');
require_once('funzioni/const.php');
@include_once('partials/privacy.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ricerca</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_SEARCH_CSS; ?>>
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
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="risultato" class="my-5">
<?php
    $output = "";
    $asData = [
        'user' => $utente
    ];
    $asData = array_merge($asData,$_GET);
    try{
        $advancedSearch = new AdvancedSearch($asData);
        $output .= $advancedSearch->getHtmlTable();
    }catch(InvalidValueException $ive){
        http_response_code(400);
        $message = $ive->getMessage();
        $output .= <<<HTML
<div id="null" class="alert alert-danger text-center my-5" role="alert">{$message}</div>
HTML;
    }catch(Exception $e){
        http_response_code(500);
        echo "\r\n".$e->getMessage()."\r\n";
        $message = M::ERR_ADVANCEDSEARCH;
        $output .= <<<HTML
<div id="null" class="alert alert-danger text-center my-5" role="alert">{$message}</div>
HTML;
    } finally{
        echo $output;
    }  
?>
        </div>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI1;
}
?>