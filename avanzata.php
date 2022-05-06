<?php
session_start();

use EcommerceTest\Interfaces\Paths as P;

require_once('interfaces/paths.php');
require_once('navbar.php');
require_once('funzioni/functions.php');
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ricerca avanzata</title>
        <meta charset="utf-8">
        <!-- <link rel="stylesheet" href="css/utente.css" type="text/css">-->
        <link rel="stylesheet" href=<?php echo P::REL_ADVSEARCH_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>>
        </script><script src=<?php echo P::REL_ADVSEARCH_JS; ?>></script>
        
    </head>
    <body>
    <?php echo menu($_SESSION['welcome']);?>
        <form id="fAvanzata" method="get" action="ricerca.php">
            <fieldset id="f1">
                <div id="f1d1">
                    <legend id="l1">Inserisci parola chiave</legend>
                    <input type="text" id="ricerca" name="ricerca" placeholder="Inserisci parola chiave">
                </div>
                <div id="f1d2">
                    <label id="lCat" for="selCat">In questa categoria
                    <select name="selCat" id="selCat">
							<option value="Tutte le categorie">Tutte le categorie</option>
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
            </fieldset>
            <fieldset id="f2">
                <legend>Prezzo</legend>
                <input type="checkbox" id="cPrezzo" name="cPrezzo" value="1">
                <label id="lPrezzo" for="cPrezzo">Mostra oggetti con prezzi da</label>
                EUR <input type="number" id="minP" name="minP" disabled> 
                a EUR <input type="number" id="maxP" name="maxP" disabled>
            </fieldset>
            <fieldset id="f3">
                <legend>Condizione</legend>
                <div>
                    <input type="checkbox" id="cN" name="cN"value="1">
                    <label id="lCn" for="cN">Nuovo</label>
                </div>
                <div>
                    <input type="checkbox" id="cU" name="cU"value="1">
                    <label id="lCu" for="cU">Usato</label>
                </div>
                <div>
                    <input type="checkbox" id="cNs" name="cNs"value="1">
                    <label id="lCns" for="lCns">Non specificato</label>
                </div>
            </fieldset>
            <fieldset id="f4">
                <legend>Intervallo di tempo in cui è stata inserita l'inserzione</legend>
                <div id="f4d1">
                    <input type="checkbox" id="dataI" name="dataI" value="1">
                    <label id="lDi" for="dataI">Data più vecchia</label>
                    <input type="date" id="oDate" name="oDate" disabled>
                </div>
                <div id="f4d2">
                    <input type="checkbox" id="dataF" name="dataF" value="1">
                    <label id="lDf" for="dataF">Data più recente</label>
                    <input type="date" id="rDate" name="rDate" disabled>
                </div>
            </fieldset>
            <fieldset id="f5">
                <legend>Luogo di provenienza del prodotto</legend>
                <div id="f5d1">
                    <input type="checkbox" id="cStato" name="cStato" value="1">
                    <label id="lStato" for="cStato">Nazione</label>
                    <input type="text" id="stato" name="stato" disabled>
                </div>
                <div id="f5d2">
                    <input type="checkbox" id="cCitta" name="cCitta" value="1">
                    <label id="lCitta" for="cCitta">Città</label>
                    <input type="text" id="citta" name="citta" disabled>
                </div>
            </fieldset>
            <div id="divButtons">
                <input type="submit" id="submit" value="CERCA">
                <input type="reset" id="reset" value="ANNULLA">
            </div>
        </form>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>