<?php

namespace EcommerceTest\Pages;

use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;

/**
 * Advanced search page
 */
class AdvancedSearchGet{
    
    public static function content(array $params): string{
        $html = <<<HTML
<html lang="it">
    <head>
        <title>Ricerca avanzata</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_ADVSEARCH_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="{$params['paths']['js']['REL_ADVSEARCH_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_DIALOG_MESSAGE_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        $session = $params['session'];
        $html .= <<<HTML
        <form id="fAvanzata" method="get" action="/search">
            <fieldset id="f1">
                <div id="f1d1">
                    <legend id="l1">Inserisci parola chiave</legend>
                    <input type="text" id="ricerca" class="form-control" name="ricerca" placeholder="Inserisci parola chiave">
                </div>
                <div id="f1d2">
                    <label id="lCat" class="form-label" for="selCat">In questa categoria</label>
                    <select name="selCat" id="selCat" class="form-select">
							<option value="Tutte le categorie" selected>Tutte le categorie</option>
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
                <input type="checkbox" id="cPrezzo" class="form-check-input" name="cPrezzo" value="1">
                <label id="lPrezzo" class="form-check-label" for="cPrezzo">Mostra oggetti con prezzi da</label>
                EUR <input type="number" id="minP" class="form-control" name="minP" disabled> 
                a EUR <input type="number" id="maxP" class="form-control" name="maxP" disabled>
            </fieldset>
            <fieldset id="f3">
                <legend>Condizione</legend>
                <div>
                    <input type="checkbox" id="cN" class="form-check-input" name="cN"value="1">
                    <label id="lCn" class="form-check-label" for="cN">Nuovo</label>
                </div>
                <div>
                    <input type="checkbox" id="cU" class="form-check-input" name="cU"value="1">
                    <label id="lCu" class="form-check-label" for="cU">Usato</label>
                </div>
                <div>
                    <input type="checkbox" id="cNs" class="form-check-input" name="cNs"value="1">
                    <label id="lCns" class="form-check-label" for="lCns">Non specificato</label>
                </div>
            </fieldset>
            <fieldset id="f4">
                <legend>Intervallo di tempo in cui è stata inserita l'inserzione</legend>
                <div id="f4d1">
                    <input type="checkbox" id="dataI" class="form-check-input" name="dataI" value="1">
                    <label id="lDi" class="form-check-label" for="dataI">Data più vecchia</label>
                    <input type="date" id="oDate" class="form-control" name="oDate" disabled>
                </div>
                <div id="f4d2">
                    <input type="checkbox" id="dataF" class="form-check-input" name="dataF" value="1">
                    <label id="lDf" class="form-check-label" for="dataF">Data più recente</label>
                    <input type="date" id="rDate" class="form-control" name="rDate" disabled>
                </div>
            </fieldset>
            <fieldset id="f5">
                <legend>Luogo di provenienza del prodotto</legend>
                <div id="f5d1">
                    <input type="checkbox" id="cStato" class="form-check-input" name="cStato" value="1">
                    <label id="lStato" class="form-check-label"  for="cStato">Nazione</label>
                    <input type="text" id="stato" class="form-control" name="stato" disabled>
                </div>
                <div id="f5d2">
                    <input type="checkbox" id="cCitta" class="form-check-input" name="cCitta" value="1">
                    <label id="lCitta" class="form-check-label"  for="cCitta">Città</label>
                    <input type="text" id="citta" class="form-control" name="citta" disabled>
                </div>
            </fieldset>
            <div id="divButtons">
                <button type="submit" id="submit" class="btn btn-primary">CERCA</button>
                <button type="reset" id="reset" class="btn btn-danger">ANNULLA</button>
            </div>
        </form>
HTML;
        $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>        
HTML;
        return $html;
    }
    
}
?>