<?php

namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\NavbarGuest;
use Exception;
use EcommerceTest\Interfaces\Constants as C;

/**
 * HTML reset form class
 */
class ResetGet{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recupera password</title>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_RESET_JS; ?>></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarGuest::content();
        $request = $params['request'];
        $regex = '/^[a-z0-9]{64}$/i';
        if(isset($request['codReset']) && preg_match($regex,$request['codReset'])){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->safeLoad();
                $codReset = $request['codReset'];
                $time = time()-C::GENERATED_LINK_TIME;
                $user = new Utente([]);
                $exists = $user->Exists("`cambioPwd` = '$codReset' AND `dataCambioPwd` >= '$time'");
                if($exists == 1){

                }
            }catch(Exception $e){

            }
        }
        return "";
    }
}
?>