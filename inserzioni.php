<?php

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Messages as M;
//use EcommerceTest\Interfaces\MySqlVals as Msv;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;

session_start();

require_once('config.php');
require_once('interfaces/messages.php');
require_once('interfaces/paths.php');
require_once('navbar.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('objects/prodotto.php');
require_once('objects/utente.php');
require('footer.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    $page_data = [
        'insertions_css' => P::REL_INSERTIONS_CSS,
        'bootstrap_css' => P::REL_BOOTSTRAP_CSS,
        'jquery_css' => P::REL_JQUERY_CSS,
        'jquerytheme_css' => P::REL_JQUERYTHEME_CSS,
        'footer_css' => P::REL_FOOTER_CSS,
        'jquery_js' => P::REL_JQUERY_JS,
        'jqueryUi_js' => P::REL_JQUERYUI_JS,
        'bootstrap_js' => P::REL_BOOTSTRAP_JS,
        'dialog_message_js' => P::REL_DIALOG_MESSAGE_JS,
        'footer_js' => P::REL_FOOTER_JS,
        'logout_js' => P::REL_LOGOUT_JS,
        'insertions_js' => P::REL_INSERTIONS_JS,
        'menu' => menu($_SESSION['welcome']),
        'popper_js' => P::REL_POPPER_JS
    ];
    $page_data['result'] = result($utente);
    $page = html_page($page_data);
    echo $page;
    echo footer();
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else
    echo M::ERR_LOGIN1;

//HTML page to display for user insertions
function html_page(array $data): string{
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
    <title>Le mie inserzioni</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$data['insertions_css']}">
        <link rel="stylesheet" href="{$data['bootstrap_css']}">
        <link rel="stylesheet" href="{$data['jquery_css']}">
        <link rel="stylesheet" href="{$data['jquerytheme_css']}" >
        <link rel="stylesheet" href="{$data['footer_css']}" >
        <script src="{$data['jquery_js']}"></script>
        <script src="{$data['jqueryUi_js']}"></script>
        <script src="{$data['popper_js']}"></script>
        <script src="{$data['bootstrap_js']}"></script>
        <script src="{$data['footer_js']}"></script>
        <script type="module" src="{$data['dialog_message_js']}"></script>
        <script type="module" src="{$data['logout_js']}"></script>
        <script src="{$data['insertions_js']}"></script>
    </head>
    </head>
    <body>
        {$data['menu']}
        <div id="risultato">
            {$data['result']}
        </div>
    </body>
</html>
HTML;
    return $html;
}

//HTML inside the div risultato
function result(Utente $user): string{
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
                $result = '<p class="error">'.$e->getMessage().'</p>';
            }
        }//if(!empty($idList)){  
        else
            $result = '<p class="error">'.M::ERR_NOINSERTIONUPLOADED.'</p>';
    }//if($idList !== null){
    else
        $result = '<p class="error">'.M::ERR_INSERTIONLIST.'</p>';
    return $result;
}
?>
