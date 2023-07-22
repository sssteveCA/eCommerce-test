<?php

namespace EcommerceTest\Objects;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarGuest;

/**
 * Account activation form
 */
class ActivateFormGet{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attivazione account</title>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
<body>
HTML;
        $html .= NavbarGuest::content();
        $html .= <<<HTML
        <fieldset id="f1">
        <legend>Attivazione account</legend>
        <h2>Inserisci il codice di attivazione</h2>
        <form action="attiva.php" method="post" id="fAttiva">
            <div>
                <label class="form-label" for="codAut">Codice</label>
                <input type="text" id="codAut" class="form-control" name="codAut">
            </div>
            <div>
                <button type="click" class="btn btn-primary">ATTIVA</button>
            </div>
        </form>
        </fieldset>
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