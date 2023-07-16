<?php
session_start();

require_once("vendor/autoload.php");

use EcommerceTest\Interfaces\Constants as C;

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $response['title'] = 'Pagamento cancellato';
    $response[C::KEY_MESSAGE] = 'La tua transazione Paypal è stata cancellata<br><a href="/">Torna alla pagina principale</a>;'
?>
<?php
}
else{
    $response['title'] = 'Login';
    $response[C::KEY_MESSAGE] = ACCEDI1;
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title><?php echo $response['title']; ?></title>
        <meta charset="utf-8">
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
<?php echo $response[C::KEY_MESSAGE]; ?>
    </body>
</html>