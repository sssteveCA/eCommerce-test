<?php
session_start();

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Paths as P;

require_once('config.php');
require_once('interfaces/paths.php');
require_once('navbar.php');
//require_once('interfaces/mysqlVals.php');
require_once('interfaces/userErrors.php');
require_once('vendor/autoload.php');
require_once('objects/utente.php');
require_once('funzioni/config.php');
require_once("funzioni/const.php");
require('footer.php');

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    $dati = array();
    $dati['nome'] = $utente->getNome();
    $dati['cognome'] = $utente->getCognome();
    $dati['nascita'] = $utente->getNascita();
    $dati['sesso'] = $utente->getSesso();
    $dati['indirizzo'] = $utente->getIndirizzo();
    $dati['numero'] = $utente->getNumero();
    $dati['citta'] = $utente->getCitta();
    $dati['cap'] = $utente->getCap();
    $dati['email'] = $utente->getEmail();
    $dati['paypalMail'] = ($utente->getPaypalMail() !== null)? $utente->getPaypalMail() : '(Account non associato a nessuna mail business)';
        $cId = $utente->getClientId();
        $clientId = "";
        if($cId != null && preg_match(Utente::$regex['clientId'],$cId)){
            $clientId = <<<HTML
<tr>
    <th scope="row">ID Venditore</th>
    <td>{$cId}</td>
</tr>
HTML;
        }
        $output = <<<HTML
<div id="dati">
    <table class="table table-striped table-hover">
        <tbody>
            <tr>
                <th scope="row">Nome</th>
                <td>{$dati['nascita']}</td>
            </tr>
            <tr>
                <th scope="row">Cognome</th>
                <td>{$dati['cognome']}</td>
            </tr>
            <tr>
                <th scope="row">Nascita</th>
                <td>{$dati['nascita']}</td>
            </tr>
            <tr>
                <th scope="row">Sesso</th>
                <td>{$dati['numero']}</td>
            </tr>
            <tr>
                <th scope="row">Indirizzo</th>
                <td>{$dati['indirizzo']}, {$dati['numero']}</td>
            </tr>
            <tr>
                <th scope="row">Città</th>
                <td>{$dati['citta']}</td>
            </tr>
            <tr>
                <th scope="row">CAP</th>
                <td>{$dati['cap']}</td>
            </tr>
            <tr>
                <th scope="row">Indirizzo email</th>
                <td>{$dati['email']}</td>
            </tr>
            <tr>
                <th scope="row">Email business</th>
                <td>{$dati['paypalMail']}</td>
            </tr>
            {$clientId}
        </tbody>
    </table>
</div>
HTML;

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Informazioni utente</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_INFO_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_INFO_JS; ?>></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <fieldset id="f1">
            <legend>Informazioni utente</legend>
            <?php echo $output; ?>
        </fieldset>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI1;
}
?>
