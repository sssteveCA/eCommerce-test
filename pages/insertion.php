<?php
namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;

/**
 * The page for insertion creation
 */
class Insertion{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Crea inserzione</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_CREATE_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}"> 
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}"> 
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_CREATE_JS']}"></script>
HTML;
        if(file_exists('../partials/privacy.php') && is_file('../partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        try{
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $user = unserialize($params['session']['utente']);
            if($user->getPaypalMail() !== null && $user->getPaypalMail() != ''){
                $html .= <<<HTML
        <div id="insertion">
            <h1>Inserisci le informazioni richieste per caricare l'inserzione</h1>
            <form id="fInsertion" method="post" action="funzioni/upload.php" enctype="multipart/form-data">
                <input type="hidden" id="idU" name="idU" value="<?php echo $user->getId(); ?>">
                <fieldset id="f1">
                    <legend>Informazioni sul prodotto</legend>
                    <div>
                        <label for="name" class="form-label">Nome</label>
                        <textarea id="name" class="form-control" name="name"></textarea>
                    </div>
                    <div>
                        <label for="image" class="form-label">Immagine</label>
                        <input type="file" id="image" class="form-control" name="image" accept="image/jpeg">
                    </div>
                    <div>
                        <label for="type">Tipo</label>
                        <select name="type" id="type" class="form-select">
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
                        <label for="price" class="form-label">Prezzo (in Euro)</label>
                        <input type="number" id="price" class="form-control" name="price" step=".01">
                    </div>
                    <div>
                        <label for="shipping" class="form-label">Spese di spedizione (in Euro)</label>
                        <input type="number" id="shipping" class="form-control" name="shipping" step=".01">
                    </div>
                    <div>
                        <div id="cond" class="form-check">
                            <div>Condizione</div>
                            <div>
                                <div class="form-check">
                                    <input type="radio" id="cN" class="form-check-input" name="condition" value="Nuovo">
                                    <label id="lCn" class="form-check-label" for="cN">Nuovo</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="cU" class="form-check-input" name="condition" value="Usato">
                                    <label id="lCu" class="form-check-label" for="cU">Usato</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="cNs" class="form-check-input" name="condition" value="Non specificato">
                                    <label id="lCns" class="form-check-label" for="lCns">Non specificato</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="state" class="form-label">Nazione di provenienza</label>
                        <input type="text" id="state" class="form-control" name="state">
                    </div>
                    <div>
                        <label for="city" class="form-label">Città di provenienza</label>
                        <input type="text" id="city" class="form-control" name="city">
                    </div>
                    <div>
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea id="description" class="form-control"  name="description"></textarea>
                    </div>
                    <div id="buttons">
                        <button type="submit" id="bOk" class="btn btn-success">OK</button>
                        <button type="reset" id="bAnnulla" class="btn btn-danger">ANNULLA</button>
                    </div>
                </fieldset>
            </form>
        </div>
HTML;
            }//if($user->getPaypalMail() !== null && $user->getPaypalMail() != ''){
            else $html .= <<<HTML
        <div class="error-message">Per caricare un\' inserzione, collega il tuo account ad una mail Paypal</div>
HTML;
        }catch(Exception $e){
            $html .= <<<HTML
        <div class="error-message">Si è verificato un errore durante il caricamento della pagina</div>
HTML;
        }
        $html .= <<<HTML
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