<?php

namespace EcommerceTest\Pages;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * The logged account details
 */
class Info{

    public static function content(array $params): string{
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
        $user = unserialize($params['session']['utente']);
        $data = [
            'nome'=> $user->getNome(), 'cognome'=> $user->getCognome(), 'nascita'=> $user->getNascita(), 'sesso'=> $user->getSesso(),
            'indirizzo'=> $user->getIndirizzo(), 'numero'=> $user->getNumero(), 'citta'=> $user->getCitta(), 'cap'=> $user->getCap(),
            'email'=> $user->getEmail(),
        ];
        $data['paypalMail'] = ($user->getPaypalMail() !== null)? $user->getPaypalMail() : '(Account non associato a nessuna mail business)';
        $cId = $user->getClientId();
        $clientId = "";
        if($cId != null && preg_match(Utente::$regex['clientId'],$cId)){
            $clientId = <<<HTML
<tr>
    <th scope="row">ID Venditore</th>
    <td>{$cId}</td>
</tr>
HTML;
        }
        $output = <<<HTML
<div id="dati">
    <table class="table table-striped table-hover">
        <tbody>
            <tr>
                <th scope="row">Nome</th>
                <td>{$data['nascita']}</td>
            </tr>
            <tr>
                <th scope="row">Cognome</th>
                <td>{$data['cognome']}</td>
            </tr>
            <tr>
                <th scope="row">Nascita</th>
                <td>{$data['nascita']}</td>
            </tr>
            <tr>
                <th scope="row">Sesso</th>
                <td>{$data['numero']}</td>
            </tr>
            <tr>
                <th scope="row">Indirizzo</th>
                <td>{$data['indirizzo']}, {$data['numero']}</td>
            </tr>
            <tr>
                <th scope="row">Città</th>
                <td>{$data['citta']}</td>
            </tr>
            <tr>
                <th scope="row">CAP</th>
                <td>{$data['cap']}</td>
            </tr>
            <tr>
                <th scope="row">Indirizzo email</th>
                <td>{$data['email']}</td>
            </tr>
            <tr>
                <th scope="row">Email business</th>
                <td>{$data['paypalMail']}</td>
            </tr>
            {$clientId}
        </tbody>
    </table>
</div>
HTML;
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Informazioni utente</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_INFO_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_INFO_JS; ?>></script>
HTML;
        if(file_exists('../partials/privacy.php') && is_file('../partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        $html .= <<<HTML
        <fieldset id="f1">
            <legend>Informazioni utente</legend>
            {$output}
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