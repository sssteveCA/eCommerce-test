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
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/registrati/registrati.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/registrati.js"></script>
    </head>
    <body>
        <div class="my-container">
            <div id="indietro">
                <a href="index.php"><img src="img/altre/indietro.png" alt="indietro" title="indietro"></a>
                <a href="index.php">Indietro</a>
            </div>
            <form id="formReg" method="post" action="funzioni/nuovoAccount.php">
                <div>
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" class="form-control" name="nome" required>
                </div>
                <div>
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" id="cognome" class="form-control" name="cognome" required>
                </div>
                <div>
                    <label for="nascita" class="form-label">Data di nascita</label>
                    <input type="date" id="nascita" class="form-control" name="nascita" min="1920-01-01" max="2001-12-31" required>
                </div>
                <div class="radios-div d-flex">
                    <div class="d-flex align-items-center me-4">
                        Genere
                    </div>
                    <div class="radios d-flex">
                        <div>
                            <input type="radio" id="m" name="sesso" value="M" checked>
                            <label for="m">Maschio</label>
                        </div>
                        <div>
                            <input type="radio" id="f" name="sesso" value="F">
                            <label for="f">Femmina</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" id="indirizzo" class="form-control" name="indirizzo" required>
                </div>
                <div>
                    <label for="numero" class="form-label">Numero civico</label>
                    <input type="text" id="numero" class="form-control" name="numero" required>
                </div>
                <div>
                    <label for="citta" class="form-label">Residente a</label>
                    <input type="text" id="citta" class="form-control" name="citta" required>
                </div>
                <div>
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" id="cap" class="form-control" name="cap" required>
                </div>
                <div>
                    <label for="user" class="form-label">Nome utente</label>
                    <input type="text" id="user" class="form-control" name="user" required>
                </div>
                <div>
                    <label for="email" class="form-label">Email business*</label>
                    <input type="email" id="paypalMail" class="form-control" name="paypalMail">
                </div>
                <div>
                    <label for="email" class="form-label">ID venditore*</label>
                    <input type="email" id="clientId" class="form-control" name="clientId">
                </div>
                <div>
                    <label for="email" class="form-label">Indirizzo mail</label>
                    <input type="email" id="email" class="form-control" name="email" required>
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                <div>
                    <label for="password" class="form-label">Conferma Password</label>
                    <input type="password" id="confPass" class="form-control" name="confPass" required>
                </div>
                <div>
                    <input type="checkbox" id="show" class="form-check-input">
                    <label for="show" class="form-check-label">Mostra password</label>
                </div>
                <div class="buttons">
                    <input type="submit" id="submit" class="btn btn-primary" value="REGISTRATI">
                    <input type="reset" id="reset" class="btn btn-danger" value="ANNULLA">
                </div>
                <p>*Richiesti quando l'utente deve caricare le inserzioni</p>
            </form>
        </div>
    </body>
</html>
<?php
}
?>