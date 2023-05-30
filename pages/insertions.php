<?php

namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use EcommerceTest\Interfaces\Messages as M;
use Exception;

/**
 * Logged user insertions list page
 */
class Insertions{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
    <title>Le mie inserzioni</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_INSERTIONS_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}" >
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script src="{$params['paths']['js']['REL_INSERTIONS_JS']}"></script>
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
        $dotenv = Dotenv::createImmutable(__DIR__."/../");
        $dotenv->load();
        $user = unserialize($_SESSION['utente']);
        $result = Insertions::result($user);
        $html .= <<<HTML
        <div id="risultato">{$result}</div>
HTML;
    }catch(Exception $e){
        $message = M::ERR_PAGERROR;
        $html .= <<<HTML
    <p class="error">{$message}</p>
HTML;
    }
    $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>
HTML;
        return $html;
    }

    private static function result(Utente $user): string{
        $result = '';
        $idA = $user->getId();
        $tabProd = $_ENV['TABPROD'];
        $query = <<<SQL
SELECT `id` FROM `{$tabProd}` WHERE `idU`='{$idA}' ORDER BY `data` DESC LIMIT 30;
SQL;
        $idList = Prodotto::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$query);
        if($idList !== null){
            //Connected to MySql
            if(!empty($idList)){
                $productsHtml = '';
                try{
                    //User has already uploaded at least one insertion
                    foreach($idList as $id){
                        //Get info about product by id
                        $product = new Prodotto(['id' => $id]);
                        $productsHtml .= '<tr>';
                        $productsHtml .= '<td class="name">'.$product->getNome().'</td>';
                        $productsHtml .= '<td class="timg"><img src="'.$product->getImmagine().'"></td>';
                        $productsHtml .= '<td class="type">'.$product->getTipo().'</td>';
                        $productsHtml .= '<td class="price">'.$product->getPrezzo().'</td>';
                        $productsHtml .= <<<HTML
        <td class="details">
            <form method="get" action="prodotto.php">
                <input type="hidden" name="id" value="{$id}">
                <button type="submit" class="btn btn-info">DETTAGLI</button>
            </form>
        </td>
HTML;
                    $productsHtml .= '</tr>';
                    }//foreach($idList as $id){
                    $result =<<<HTML
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">NOME</th>
                    <th scope="col">IMMAGINE</th> 
                    <th scope="col">TIPO</th> 
                    <th scope="col">PREZZO</th> 
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                {$productsHtml}
            </tbody>
        </table>
HTML;
                }catch(Exception $e){
                    $message = M::ERR_PAGERROR;
                    $result = <<<HTML
                <p class="error">{$message}</p>
HTML;
                }
            }//if(!empty($idList)){  
            else
                $result = '<p class="error">'.M::ERR_NOINSERTIONUPLOADED.'</p>';
        }
        else
        $result = '<p class="error">'.M::ERR_INSERTIONLIST.'</p>';
    return $result;
    }
}
?>