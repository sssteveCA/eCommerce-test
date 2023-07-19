<?php

namespace EcommerceTest\Pages;
use Dotenv\Dotenv;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;

/**
 * Buy product page
 */
class BuyPost{

    public static function content(array $params): string{
        $html = '';
        $session = $params['session'];
        if(isset($session['logged'],$session['utente'],$session['welcome']) && $session['welcome'] != '' && $session['logged'] === true){
            $post = $params['post'];
            if(isset($session['prodotto'],$session['venditore'],$post['qt']) && is_numeric($post['qt'])){
                try{
                    $dotenv = Dotenv::createImmutable(__DIR__.'/../');
                    $dotenv->load();
                    $customer = unserialize($session['prodotto']);
                    $product = unserializeProduct($session['prodotto']);
                    $total = $post['qt']*($product->getPrezzo()+$product->getSpedizione());
                    $seller = unserialize($session['venditore']);
                    $html .= <<<HTML
<html lang="it">
    <head>
        <title>Acquista prodotto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_BUY_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_BUY_JS; ?>></script>     
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        $price = sprintf("%.2f€",$product->getPrezzo()*$post['qt']);
        $shippingPrice = sprintf("%.2f€",$product->getSpedizione()*$post['qt']);
        $totalStr = sprintf("%.2f€",$total);
        $html .= <<<HTML
        <form id="conferma" method="post" action="conferma.php">
            <div id="divConf">
                <fieldset id="f1">
                    <legend>Dati Acquirente</legend>
                    <p id="cNome">Nome : {$customer->getNome()}</p>
                    <p id="cCognome">Cognome : {$customer->getCognome()}</p>
                    <p id="cData">Nato il : {$customer->getNascita()}</p>
                    <p id="cCitta">Residente a : {$customer->getCitta()}</p>
                    <p id="cIndirizzo">Indirizzo : {$customer->getIndirizzo()}, {$customer->getNumero()}</p>
                    <p id="cCap">CAP : {$customer->getCap()}</p>
                </fieldset>
                <fieldset id="f2">
                    <legend>Dati prodotto</legend>
                    <p id="pVend">Venditore: {$seller->getUsername()}</p>
                    <p id="pNome">Nome : {$product->getNome()}</p>
                    <p id="pCat">Categoria : {$product->getTipo()}</p>
                    <p id="pPrezzo">Prezzo : {$price}</p>
                    <p id="pSped">Spese di spedizione : {$shippingPrice}</p>
                    <p id="pQt">Quantità : {$post['qt']} ?></p>
                    <p id="pVend">Spedito da : {$product->getStato()}, {$product->getCitta()}</p>
                    <p id="pTotale">Totale : {$totalStr}</p>
                </fieldset>
                <div id="buttons">
                    <input type="hidden" id="idC" name="idC" value="{$customer->getId()}">
                    <input type="hidden" id="idP" name="idP" value="{$product->getId()}">
                    <input type="hidden" id="idV" name="idV" value="{$seller->getId()}">
                    <input type="hidden" id="nP" name="nP" value="{$post['qt']}">
                    <input type="hidden" id="tot" name="tot" value="{$totalStr}">
                    <button type="submit" id="bOk" class="btn btn-primary">CONFERMA</button>
                    <button type="button" id="bInd" class="btn btn-danger">INDIETRO</button>
                </div>
            </div>
        </form>
HTML;
        $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>        
HTML;
                }catch(Exception $e){

                }
                
            }//if(isset($session['prodotto'],$session['venditore'],$post['qt']) && is_numeric($post['qt'])){
        }//if(isset($session['logged'],$session['utente'],$session['welcome']) && $session['welcome'] != '' && $session['logged'] === true){
        
        return $html;
    }

}

?>