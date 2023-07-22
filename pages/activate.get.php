<?php

namespace EcommerceTest\Pages;
use Dotenv\Dotenv;
use EcommerceTest\Objects\General;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarGuest;
use Exception;

/**
 * Account activation page
 */
class ActivateGet{

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
        $regex = '/^[a-z0-9]{64}$/i';
        if(isset($params['codAut']) && preg_match($regex,$params['codAut'])){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__);
                $dotenv->safeLoad();
                $data = [
                    'campo' => 'codAut',
                    'codAut' => $params['codAut'],
                    'registrato' => '1'
                ];
                $user = new Utente($data);
                $codAut = $user->getCodAut();
                $error = $user->getNumError();
                if(!isset($codAut) && $error === 0)
                    $html .= General::simpleMessage('L\' account è stato attivato');
                else
                    $html .= General::simpleMessage('Impossibile attivare l\'account');
            }catch(Exception $e){
                $html .= General::simpleMessage("Errore durante l' attivazione dell' account");
            }
            
        }//if(isset($params['codAut']) && preg_match($regex,$params['codAut'])){
        else{
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
        }
        $html .= Footer::content();
        $html .= <<<HTML
   </body>
</html>
HTML;
        return $html;
    }
}
?>