<?php
session_start();
require_once('objects/utente.php');
require_once('funzioni/config.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
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
        $output = <<<HTML
<div id="dati">
        <p id="nome">Nome: {$dati['nome']}</p>
        <p id="cognome">Cognome: {$dati['cognome']}</p>
        <p id="nascita">Nascita: {$dati['nascita']}</p>
        <p id="sesso">Sesso: {$dati['sesso']}</p>
        <p id="indirizzo">Indirizzo: {$dati['indirizzo']}, {$dati['numero']}</p>
        <p id="citta">Città: {$dati['citta']}</p>
        <p id="cap">CAP: {$dati['cap']}</p>
        <p id="email">Indirizzo email: {$dati['email']}</p>
        <p id="emailBusiness">Email business: {$dati['paypalMail']}</p>
HTML;
    $cId = $utente->getClientId();
    if($cId != null && preg_match(Utente::$regex['clientId'],$cId)){
        $output .= '<p id="clientId">ID venditore: '.$cId.'</p>';
    }
    $output .= '</div>';

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Informazioni utente</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/utente.css" type="text/css">
        <link rel="stylesheet" href="css/info.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/info.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="menu">
                <div id="welcome"><?php echo $_SESSION['welcome']; ?></div>
                <div id="profilo">
                    Profilo
                    <div>
                        <a href="info.php">Informazioni</a>
                        <a href="edit.php">Modifica</a>
                    </div>
                </div>
                <div id="ordini">
                    Ordini
                    <div>
                        <a href="ordini.php">I miei ordini</a>
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>
                <div id="prodotto">
                    Prodotto
                    <div>
                        <a href="benvenuto.php">Cerca</a>
                        <a href="crea.php">Crea inserzione</a>
                        <a href="inserzioni.php">Le mie inserzioni</a>
                    </div>
                </div>
                <div id="contatti"><a href="contatti.php">Contatti</a></div>
                <div id="logout"><a href="funzioni/logout.php">Esci</a></div>
            </div>
        </div>
        <fieldset id="f1">
            <legend>Informazioni utente</legend>
            <?php echo $output; ?>
        </fieldset>
    </body>
</html>
<?php
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI1;
}
?>
