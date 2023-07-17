<?php

namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;

/**
 * Product details page
 */
class Productet{

    public static function content(array $params): string{
        $session = $params['session'];
        $html = <<<HTML
HTML;
if(isset($session['logged'],$session['utente'],$session['welcome']) && $session['welcome'] != '' && $session['logged'] === true){
    try{
        $dotenv = Dotenv::createImmutable(__DIR__."/../");
        $dotenv->load();
        $user = unserialize($session['utente']);
        $get = $params['get'];
        if(isset($get['id']) && is_numeric($get['id'])){
            $productData = [ 'id' => $get['id'] ];
            $product = new Prodotto($productData);
            $_SESSION['prodotto'] = serialize($product);
            $sellerData = [ 'id' => $product->getIdu(), 'registrato' => '1', 'password' => '123456' ];
            $seller = new Utente($sellerData);
            $_SESSION['venditore'] = serialize($seller);
            $html .= <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <!-- Nome del prodotto -->
        <title>{$product->getNome()}</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_PRODUCT_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script type="module" src=<?php echo P::REL_PRODUCT_JS; ?>></script>
HTML;
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                $html .= call_user_func('cookieBanner');
            }
            $html .= <<<HTML
    </head>
    <body>
HTML;
            $html .= NavbarLogged::content($params);
            $html .= <<<HTML
        <div id="container1" class="container">
            <!-- immagine del prodotto -->
            <div id="immagine"><img src="{$product->getImmagine()}"></div>
            <div id="dInfo1">
                <!-- nome completo del prodotto in grassetto -->
                <fieldset id="info1"><b>{$product->getNome()}</b></fieldset>
                <fieldset id="info2">
HTML;
            $price = sprintf("%.2f EUR",$product->getPrezzo());
            $shipping = sprintf("%.2f EUR",$product->getSpedizione()); 
            //User can buy the product if is not the same that has uploaded it
            if($user->getId() != $product->getIdu()){
                $html .= self::buyForm($product,$price,$shipping);
            }//if($user->getId() != $product->getIdu()){
            else{
                $html .= <<<HTML
                <form id="elimina" method="post" action="funzioni/elimina.php">
                    <div id="tipo" class="info">
                        <!-- Categoria del prodotto -->
                        Tipo: {$product->getTipo()} 
                    </div>
                    <div id="condizione">
                        <!-- Condizione prodotto: nuovo,usato o non specificato -->
                        Condizione : {$product->getCondizione()} 
                    </div>
                    <div id="prezzo" class="info">
                        <!-- Prezzo in euro -->
                        Prezzo: {$price}
                    </div>
                    <div id="spedizione" class="info">
                        <!-- Spese di spedizione in euro -->
                        Spese di spedizione: {$shipping} 
                    </div>
                    <div id="dCompra" class="info">
                        <input type="hidden" id="idp" name ="idp" value="{$product->getId()}">
                        <button type="submit" id="bElimina" class="btn btn-danger">ELIMINA</button>
                    </div>
                </form>
HTML;
            }
            $descriptionBr = nl2br($product->getDescrizione());
            $html .= <<<HTML
            </fieldset>
        </div>
    </div>
    <div id="dInfo2" class="container">
        <fieldset id="info3">
                <div id="seller" class="info">
                    Venditore: {$seller->getUsername()}
                </div>
                <div id="data" class="info">
                    Data Inserzione: {$product->getData()}
                </div>
                <div id="stato" class="info">
                    Stato provenienza: {$product->getStato()}
                </div>
                <div id="luogo" class="info">
                    Luogo di provenienza: {$product->getCitta()}
                </div>
        </fieldset>
    </div>
    <div id="descrizione" class="container">
        <h1>Descrizione prodotto</h1>
        <div>
            <fieldset id="info4">
                <!-- Descrizione del prodotto. La funzione nl2br converte i '\n' in <br> -->
                {$descriptionBr}
            </fieldset>
        </div>
    </div>
HTML;
            if($user->getId() != $product->getIdu()){
                $html .= <<<HTML
        <div id="email" class="container">
            <fieldset id="fEmail">
                <legend>Contatta il venditore</legend>
                <form id="formMail" method="post" action="funzioni/mail.php">
                    <div>
                        <label for="oggetto" class="form-label me-2">Oggetto </label>
                        <input type="text" id="oggetto" class="form-control" name="pOggetto">
                    </div>
                    <div>
                        <label for="messaggio" class="form-label me-2">Messaggio</label>
                        <textarea id="messaggio" class="form-control" name="pMessaggio"></textarea>
                    </div>
                    <!--Indica il blocco di istruzioni che dovrà eseguire lo script php -->
                    <input type="hidden" name="oper" value="<?php echo '3'; ?>">
                    <!-- Destinatario mail -->
                    <input type="hidden" id="emailTo" name="emailTo" value="<?php echo $seller->getEmail(); ?>">
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary">CONTATTA</button>
                        <div id="contacts-spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
HTML;
            }//if($user->getId() != $product->getIdu()){
            $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>        
HTML;
        }//if(isset($get['id']) && is_numeric($get['id'])){
    }catch(Exception $e){
        
    }
}//if(isset($session['logged'],$session['utente'],$session['welcome']) && $session['welcome'] != '' && $session['logged'] === true){

        return $html;
    }

    private static function buyForm(Prodotto $product, string $price, string $shipping): string{
        return <<<HTML
        <form id="compra" method="post" action="compra.php">
            <div id="tipo" class="info">
                <!-- Categoria del prodotto -->
                Tipo: {$product->getTipo()} 
            </div>
            <div id="condizione">
                <!-- Condizione prodotto: nuovo,usato o non specificato -->
                Condizione : {$product->getCondizione()} 
            </div>
            <div id="qt" class="info">
                <!-- numero di prodotti che l'utente vuole comprare -->
                <label for="iQt" class="form-label me-2">Quantità</label>
                <input type="number" id="iQt" class="form-control" name="qt" value="1">
            </div>
            <div id="prezzo" class="info">
                <!-- Prezzo in euro -->
                Prezzo: {$price}
            </div>
            <div id="spedizione" class="info">
                <!-- Spese di spedizione in euro -->
                Spese di spedizione: {$shipping} 
            </div>
            <div id="dCompra" class="info">
                <input type="hidden" id="idp" name ="idp" value="{$product->getId()}">
                <button type="submit" id="bCompra" class="btn btn-primary">COMPRA</button>
                </div>
        </form>
HTML;
    }
}
?>