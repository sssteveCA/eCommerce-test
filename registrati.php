<?php
session_start();
if(isset($_SESSION['user'],$_SESSION['logged']) && $_SESSION['user'] != '' && $_SESSION['logged']){
    echo '<a href="logout.php">Esci </a> dall\' account per registrarti';
}
else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Registrati</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/registrati.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/registrati.js"></script>
    </head>
    <body>
        <div id="indietro">
            <a href="index.php"><img src="img/altre/indietro.png" alt="indietro" title="indietro"></a>
            <a href="index.php">Indietro</a>
        </div>
        <form id="formReg" method="post" action="funzioni/nuovoAccount.php">
            <div>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div>
                <label for="cognome">Cognome</label>
                <input type="text" id="cognome" name="cognome" required>
            </div>
            <div>
                <label for="nascita">Data di nascita</label>
                <input type="date" id="nascita" name="nascita" min="1920-01-01" max="2001-12-31" required>
            </div>
            <div>
                <div>
                    <input type="radio" id="m" name="sesso" value="M" checked>
                    <label for="m">Maschio</label>
                </div>
                <div>
                    <input type="radio" id="f" name="sesso" value="F">
                    <label for="f">Femmina</label>
                </div>
            </div>
            <div>
                <label for="indirizzo">Indirizzo</label>
                <input type="text" id="indirizzo" name="indirizzo" required>
            </div>
            <div>
                <label for="numero">Numero civico</label>
                <input type="text" id="numero" name="numero" required>
            </div>
            <div>
                <label for="citta">Residente a</label>
                <input type="text" id="citta" name="citta" required>
            </div>
            <div>
                <label for="cap">CAP</label>
                <input type="text" id="cap" name="cap" required>
            </div>
            <div>
                <label for="user">Nome utente</label>
                <input type="text" id="user" name="user" required>
            </div>
            <div>
                <label for="email">Email business*</label>
                <input type="email" id="paypalMail" name="paypalMail">
            </div>
            <div>
                <label for="email">ID venditore*</label>
                <input type="email" id="clientId" name="clientId">
            </div>
            <div>
                <label for="email">Indirizzo mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <input type="checkbox" id="show">
                <label for="show">Mostra password</label>
            </div>
            <div>
                <input type="submit" id="submit" value="REGISTRATI">
                <input type="reset" id="reset" value="ANNULLA">
            </div>
        </form>
        <p>*Richiesti quando l'utente deve caricare le inserzioni</p>
    </body>
</html>
<?php
}
?>