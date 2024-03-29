<?php

namespace EcommerceTest\Pages\Partials;

class Footer{

    public static function content(): string{
        $html = <<<HTML
<div class="footer">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                ECommerce è un sito web che ti permette di effettuare acquisti di vari tipi di prodotti. E' sufficiente creare un account in maniera gratuita.
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                Questa sito web è sviluppato da <a href="https://github.com/sssteveCA/eCommerce-test">Stefano Puggioni</a>.
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                © 2022 - ECommerce
            </div>
        </div>
    </div>
HTML;
    $exist = (file_exists(__DIR__."/../../privacy_policy.php") && file_exists(__DIR__."/../../cookie_policy.php") && file_exists(__DIR__."/../../terms.php"));
    $areFiles = (is_file(__DIR__."/../../privacy_policy.php") && is_file(__DIR__."/../../cookie_policy.php") && is_file(__DIR__."/../../terms.php"));
    if($exist && $areFiles){
        $html .= <<<HTML
    <div class="privacy-links">
        <div>
            <a href="privacy_policy.php">Privacy Policy</a>
        </div>
        <div>
            <a href="cookie_policy.php">Cookie Policy</a>
        </div>
        <div>
            <a href="terms.php">Termini e condizioni</a>
        </div>
    </div>
HTML;
    }//if($exist && $areFiles){
    $html .= <<<HTML
</div>
HTML;
    return $html;
    }
}

?>