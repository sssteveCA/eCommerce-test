<?php
session_start();
require_once('interfaces/constants.php');
require_once('funzioni/const.php');
@include_once('partials/privacy.php');

use EcommerceTest\Interfaces\Constants as C;

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $risposta['title'] = 'Pagamento cancellato';
    $risposta[C::KEY_MESSAGE] = 'La tua transazione Paypal è stata cancellata<br><a href="benvenuto.php">Torna alla pagina principale</a>;'
?>
<?php
}
else{
    $risposta['title'] = 'Login';
    $risposta[C::KEY_MESSAGE] = ACCEDI1;
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title><?php echo $risposta['title'];?></title>
        <meta charset="utf-8">
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
<?php echo $risposta[C::KEY_MESSAGE]; ?>
    </body>
</html>