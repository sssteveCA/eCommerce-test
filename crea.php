<?php
session_start();
require_once('objects/utente.php');
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Crea inserzione</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/utente.css" type="text/css">
        <link rel="stylesheet" href="css/crea.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/crea.js"></script>
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
<?php
//se l'utente ha associato il suo account ad una mail Paypal allora può caricare un annuncio
    if($utente->getPaypalMail() !== null && $utente->getPaypalMail() != ''){
?>
        <div id="inserzione">
            <h1>Inserisci le informazioni richieste per caricare l'inserzione</h1>
            <form id="fInserzione" method="post" action="funzioni/upload.php" enctype="multipart/form-data">
                <input type="hidden" name="idU" value="<?php echo $utente->getId(); ?>">
                <fieldset id="f1">
                    <legend>Informazioni sul prodotto</legend>
                    <div>
                        <label for="nome">Nome</label>
                        <textarea id="nome" name="nome"></textarea>
                    </div>
                    <div>
                        <label for="immagine">Immagine</label>
                        <input type="file" id="immagine" name="immagine" accept="image/jpeg">
                    </div>
                    <div>
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo">
							<option value="Abbigliamento e accessori">Abbigliamento e accessori</option>
							<option value="Arte e antiquariato">Arte e antiquariato</option>
							<option value="Auto e moto: ricambi e accessori">Auto e moto: ricambi e accessori</option>
							<option value="Auto e moto: veicoli">Auto e moto: veicoli</option>
							<option value="Bellezza e salute">Bellezza e salute</option>
							<option value="Biglietti ed eventi">Biglietti ed eventi</option>
							<option value="Casa, arredamento e bricolage">Casa, arredamento e bricolage</option>
							<option value="Cibi e bevande">Cibi e bevande</option>
							<option value="Collezionismo">Collezionismo</option>
							<option value="Commercio, ufficio e industria">Commercio, ufficio e industria</option>
							<option value="Elettrodomestici">Elettrodomestici</option>
							<option value="Film e DVD">Film e DVD</option>
							<option value="Fotografia e video">Fotografia e video</option>
							<option value="Francobolli">Francobolli</option>
							<option value="Fumetti e memorabilia">Fumetti e memorabilia</option>
							<option value="Giardino e arredamento esterni">Giardino e arredamento esterni</option>
							<option value="Giocattoli e modellismo">Giocattoli e modellismo</option>
							<option value="Hobby creativi">Hobby creativi</option>
							<option value="Infanzia e premaman">Infanzia e premaman</option>
							<option value="Informatica">Informatica</option>
							<option value="Libri e riviste">Libri e riviste</option>
							<option value="Monete e banconote">Monete e banconote</option>
							<option value="Musica, CD e vinili">Musica, CD e vinili</option>
							<option value="Nautica e imbarcazioni">Nautica e imbarcazioni</option>
							<option value="Orologi e gioielli">Orologi e gioielli</option>
							<option value="Sport e viaggi">Sport e viaggi</option>
							<option value="Strumenti musicali">Strumenti musicali</option>
							<option value="Telefonia fissa e mobile">Telefonia fissa e mobile</option>
							<option value="TV, audio e video">TV, audio e video</option>
							<option value="Videogiochi e console">Videogiochi e console</option>
							<option value="Altre categorie">Altre categorie</option>
                        </select>
                    </div>
                    <div>
                        <label for="prezzo">Prezzo (in Euro)</label>
                        <input type="number" id="prezzo" name="prezzo" step=".01">
                    </div>
                    <div>
                        <label for="spedizione">Spese di spedizione (in Euro)</label>
                        <input type="number" id="spedizione" name="spedizione" step=".01">
                    </div>
                    <div>
                        <div id="cond">
                            <div>Condizione</div>
                            <div>
                                <input type="radio" id="cN" name="condizione" value="Nuovo">
                                <label id="lCn" for="cN">Nuovo</label>
                            </div>
                            <div>
                                <input type="radio" id="cU" name="condizione" value="Usato">
                                <label id="lCu" for="cU">Usato</label>
                            </div>
                            <div>
                                <input type="radio" id="cNs" name="condizione" value="Non specificato">
                                <label id="lCns" for="lCns">Non specificato</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="stato">Nazione di provenienza</label>
                        <input type="text" id="stato" name="stato">
                    </div>
                    <div>
                        <label for="citta">Città di provenienza</label>
                        <input type="text" id="citta" name="citta">
                    </div>
                    <div>
                        <label for="descrizione">Descrizione</label>
                        <textarea id="descrizione" name="descrizione"></textarea>
                    </div>
                    <div id="buttons">
                        <input type="submit" id="bOk" value="OK">
                        <input type="reset" id="bAnnulla" value="ANNULLA">
                    </div>
                </fieldset>
            </form>
        </div>
<?php
    }
    else{
?>
        <script>
message('Email business','auto','400px','Per caricare un\' inserzione, collega il tuo account ad una mail Paypal','close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
});
        </script>
<?php
    }
?>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>