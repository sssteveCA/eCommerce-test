<?php

namespace EcommerceTest\Pages;
use Dotenv\Dotenv;
use EcommerceTest\Exceptions\BadRequestException;
use EcommerceTest\Objects\General;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Objects\Templates\ConfirmTemplates;
use EcommerceTest\Pages\Partials\Footer;

/**
 * Comfirm buy page
 */
class ConfirmPost{

    public static function content(array $params): array{
        $done = true;
        $code = 200;
        $html = <<<HTML
HTML;
        $session = $params['session'];
        $post = $params['post'];
        try{
            $dotenv = Dotenv::createImmutable(__DIR__.'/../');
            $dotenv->load();
            $dataOk = false;
            if(isset($post['nP']) && is_numeric($post['nP'])){
                if(isset($session['prodotto'])){
                    $dataOk = true;
                    $product = unserializeProduct($session['prodotto']);
                }
                else if(!isset($session['prodotto']) && isset($post['idP'])){
                    $dataP = [ 'id' => $post['idP'] ];
                    $product = new Prodotto($dataP);
                    $dataOk = true;
                }
                if($dataOk){
                    $user = unserialize($session['utente']);
                    $uBusinessData = [
                        'registrato' => '1', 'id' => $product->getIdu()
                    ];
                    $uBusiness = new Utente($uBusinessData);
                    if($uBusiness->getNumError() == 0 || $uBusiness->getNumError() == 1){
                        $alreadyOrdered = false;
                        $idOrders = Ordine::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$_ENV['TABORD'],$_ENV['TABACC'],$user->getUsername());
                        if($idOrders != null){
                            foreach($idOrders as $idOrder){
                                $order = new Ordine(['id' => $idOrder]);
                                if($post['idP'] == $order->getIdp()){
                                    $alreadyOrdered = true;
                                    $total = floatval(sprintf('%.2f',$post['tot']));
                                    $dataU = [
                                        'quantita' => $order->getQuantita()+$post['nP'],
                                        'totale' => $order->getTotale()+$total
                                    ];
                                    $order->update($dataU);
                                }//if($post['idP'] == $order->getIdp()){
                                if($alreadyOrdered){
                                    $idOrd = $idOrder;
                                    break;
                                }
                            }//foreach($idOrders as $idOrder){
                        }//if($idOrders != null){      
                    }
                    if(isset($post['ord']) && $post['ord'] == '1')
                        $dataO = ['id' => $post['idO']];
                    else if($alreadyOrdered && isset($idOrd))
                        $dataO = ['id' => $idOrd];
                    else if(!$alreadyOrdered){
                        $dataO = [
                            'idc' => $post['idC'],
                            'idp' => $post['idP'],
                            'idv' => $product->getIdu(),
                            'quantita' => $post['nP'],
                            'totale' => sprintf('%.2f',$_POST['tot'])
                        ];
                    }
                    $return_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/success.php';
                    $return_url2 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/success2.php';
                    $cancel_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/cancel.php';
                    $cancel_url2 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/cancel2.php';
                    $notify_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/ipn.php';
                    $order = new Ordine($dataO);
                    if($order->getNumError() === 0){
                        $data = [
                            'idc' => $order->getIdc(),
                            'idp' => $order->getIdp(),
                            'idv' => $order->getIdv(),
                            'quantita' => $order->getQuantita(),
                            'totale' => $order->getTotale(),
                        ];
                        $_SESSION['ido'] = $order->getId();
                        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Conferma ordine</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/{$params['paths']['css']['REL_CONFIRM_CSS']}">
        <link rel="stylesheet" href="/{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="/{$params['paths']['css']['REL_JQUERY_CSS']}">
        <link rel="stylesheet" href="/{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
        <link rel="stylesheet" href="/{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="/{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="/{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="/{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="/{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="/{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="/{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script type="module" src="/{$params['paths']['js']['REL_CONFIRM_JS']}"></script>
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
        <div id="formContainer">
            <fieldset id="f1">
            <legend>Ordine</legend>
            <p>Fai click su 'PAGA' per acquistare il prodotto</p>
            <div id="divButtons">
                <!-- Form per l'accesso alla pagina Paypal apposita -->
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-4 col-lg-3">
HTML;
                        $paymentFormData = [
                            'paypalPage' => C::PAYPAL_PAGE, 
                            'paypalMail' => $uBusiness->getPaypalMail(),
                            'returnUrl' => $return_url, 
                            'cancelUrl' => $cancel_url, 
                            'notifyUrl' => $notify_url,
                            'rm' => C::PAYPAL_RM, 
                            'currency' => C::PAYPAL_CURRENCY, 
                            'lc' => C::PAYPAL_LC, 
                            'shipping' => $product->getSpedizione(),
                            'productName' => addslashes($product->getNome()), 
                            'productId' => $product->getId(),
                            'orderAmout' => $order->getTotale()
                        ];
                        $html .= ConfirmTemplates::paypalForm($paymentFormData);
                        $html .= <<<HTML
                            </div>
                            <div class="col-12 col-sm-4 col-lg-3">
HTML;
                        $addToCartFormData = [
                            'cartAction' => 'funzioni/cartMan.php', 
                            'orderId' => $order->getId(), 
                            'productId' => $product->getId()
                        ];
                        $html .= ConfirmTemplates::addToCartForm($addToCartFormData);
                        $html .= <<<HTML
                            </div>
                            <div class="col-12 col-sm-4 col-lg-3">
HTML;
                        $goBackFormData = [
                            'backAction' => '/product/buy', 'idp' => $data['idp'], 'qt' => $data['quantita']
                        ];
                        $html .= ConfirmTemplates::goBackForm($goBackFormData);
                        $html .= <<<HTML
                            </div>
                    </div>
                </div>
            </div>
            </fieldset>
HTML;
                        if($user->getClientId() != null && $uBusiness->getClientId() != null){
                            $sbnCode = C::PAYPAL_SBN_CODE;
                            $html .= <<<HTML
            <div id="paypalArea"></div>
            <div id="confirm" style="display:none;">
                <button id="confirmButton">Conferma</button>
            </div>
        </div>
        <script src="//www.paypalobjects.com/api/checkout.js"></script>
        <!-- PayPal In-Context Checkout script -->
        <script type="module">
            import {paypalButton} from './js/confirm/confirm.functions.js';
            var clientId = '{$uBusiness->getClientId()}';
            var sbn_code = '{$sbnCode}';
            //console.log("clientId = "+clientId);
            var client = {
                sandbox:  clientId
            };
            var environment = 'sandbox';
            /*var transaction = {
                transactions: [
                    {
                        amount: {
                            total:    '15.00',
                            currency: 'USD'
                        }
                    }
                ]
            };*/

            paypalButton(paypal,clientId,sbn_code);
        </script>

HTML;
                        }//if($user->getClientId() != null && $uBusiness->getClientId() != null){
                        $html .= Footer::content();
                        $html .= <<<HTML
    </body>
</html>
HTML;
                    }//if($order->getNumError() === 0){
                    else throw new Exception;
                }//if($dataOk){
                else throw new BadRequestException;
            }//if(isset($_POST['nP']) && is_numeric($_POST['nP'])){
            else throw new Exception;
        }catch(BadRequestException $e){
            $done = false;
            $code = 400;
            $html = General::genericHtml('Conferma','Dati mancanti o incompleti');
        }catch(Exception $e){
            $done = false;
            $code = 500;
            $html = General::genericHtml('Conferma','Errore durante il caricamento della pagina');
        }        
        return [
            C::KEY_CODE => $code, C::KEY_DONE => $done, C::KEY_HTML => $html
        ];
    }
}

?>