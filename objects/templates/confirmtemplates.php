<?php

namespace EcommerceTest\Objects\Templates;

class ConfirmTemplates{

    /**
     * Get the form used in conferma.php to add a product to cart
     * @param array $data the values used in the form
     * @return string the HTML of the form 
     */
    public static function addToCartForm(array $data): string{
        return <<<HTML
<form id="cart" method="post" action="{$data['cartAction']}">
    <!-- oper = 1, aggiunge il prodotto al carrello -->
    <input type="hidden" id="oper" name="oper" value="2">
    <!-- ID dell'ordine -->
    <input type="hidden" id="ido" name="ido" value="{$data['orderId']}">
    <input type="hidden" id="idp" name="idp" value="{$data['productId']}">
    <button type="submit" id="bCart" class="btn btn-secondary">AGGIUNGI AL CARRELLO</button>
</form>
HTML;
    }

    /**
     * Get the form used in conferma.php to go to previous page
     * @param array $data the values used in the form
     * @return string the HTML of the form 
     */
    public static function goBackForm(array $data): string{
        return <<<HTML
<form id="back" method="post" action="{$data['backAction']}">
    <input type="hidden" id="idp" name="idp" value="{$data['idp']}">
    <input type="hidden" id="qt" name="qt" value="{$data['qt']}">
    <button type="submit" id="bInd" class="btn btn-warning">INDIETRO</button>
</form>
HTML;
    }

    /**
     * Get the form used in conferma.php to make the payment
     * @param array $data the values used in the form
     * @return string the HTML of the form
     */
    public static function paypalForm(array $data): string{
        return <<<HTML
<form id="paga" method="post" action="{$data['paypalPge']}">
    <input type="hidden" name="business" value="{$data['paypalMail']}">
    <input type="hidden" name="cmd" value="_xclick">

    <!-- informazioni sulla transazione -->
    <input type="hidden" id="return" name="return" value="{$data['returnUrl']}">
    <input type="hidden" id="cancel_return" name="cancel_return" value="{$data['cancelUrl']}">
    <input type="hidden" id="notify_url" name="notify_url" value="{$data['notifyUrl']}">
    <input type="hidden" id="rm" name="rm" value="{$data['rm']}">
    <input type="hidden" id="currency" name="currency_code" value="{$data['currency']}">
    <input type="hidden" id="lc" name="lc" value="{$data['lc']}">
    <input type="hidden" id="cbt" name="cbt" value="Continua">

    <!-- informazioni sul pagamento -->
    <input type="hidden" id="shipping" name="shipping" value="{$data['shipping']}">
    <!-- colore di sfondo della pagina di pagamento: 0 = bianco, 1 = nero -->
    <input type="hidden" id="cs" name="cs" value="1">

    <!-- informazioni sul prodotto -->
    <input type="hidden" id="item_name" name="item_name" value="{$data['productName']}"> 
        <input type="hidden" id="item_number" name="item_number" value="{$data['productId']}"> 
    <input type="hidden" id="amount" name="amount" value="{$data['orderAmout']}">

    <!-- informazioni sulla vendita -->
    <input type="hidden" id="custom" name="custom" value="{$data['productId']}">


    <button type="submit" id="bOk" class="btn btn-primary">PAGA</button>
</form>
HTML;
    }
}
?>