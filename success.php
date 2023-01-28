<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;

session_start();

require_once('config.php');
require_once('interfaces/orderErrors.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('traits/sql.trait.php');
require_once('traits/ordine.trait.php');
require_once('traits/utente.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/ordine.php');
require_once('funzioni/config.php');
require_once('objects/utente.php');
require_once('funzioni/const.php');
@include_once('partials/privacy.php');
require_once('partials/navbar.php');
require_once('partials/footer.php');

//file_put_contents("log.txt","success.php => ".var_export($_POST,true)."\r\n",FILE_APPEND);

$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');
$response = [
    'msg' => ''
];
//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    /*echo '<pre>';
    echo 'post<br>';
    var_dump($_POST);
    echo 'get<br>';
    var_dump($_GET);
    echo '</pre>';*/
    if($_POST["payer_status"] == 'VERIFIED'){
        if(isset($_SESSION['ido'])){
            $dati = array();
            $dati['id'] = $_SESSION['ido'];
            try{
                $ordine = new Ordine($dati);
                //l'ordine era presente nel carrello
                if($ordine->isCarrello() === true){
                    $del = $ordine->delFromCart($utente->getUsername());
                    if($del){
                        $values = [
                            'tnx_id' => $_POST['tnx_id'], 'pagato' => '1'
                        ];
                        $ordine->update($valori);
                        if($ordine->getNumError() == 0){
                            $response['msg'] = 'Pagamento effettuato con successo';
                        }
                        else{
                            $response['msg'] = $ordine->getStrError().'<br>';
                            $response['msg'] .= ' Linea n. '.__LINE__;
                        } 
                    }
                    else{
                        $response['msg'] = $ordine->getStrError().'<br>';
                        $response['msg'] .= ' Linea n. '.__LINE__;
                    }
                }//if($ordine->isCarrello() === true){
                else{
                    $response['msg'] = 'Aggiungi al carrello il prodotto e riprova';
                }
            }
            catch(Exception $e){
                $response['msg'] = $e->getMessage().'<br>';
                $response['msg'] .= ' Linea n. '.__LINE__;
            }
        }
        else{
            $response['msg'] = 'Id ordine inesistente';
        } 
    }//if($_POST["payer_status"] == 'VERIFIED'){
}
else{
    if(!$ajax)$response['msg'] = ACCEDI1;
    else $response['msg'] = 'ERRORE: l\' utente è stato disconnesso';
}


if(!$ajax){
    $pageData = [
        'cookieBanner' => '',
        'menu' => menu($_SESSION['welcome']),
        'message' => $response['msg'],
        'footer' => footer()
    ];
    if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
       $pageData['cookieBanner'] = call_user_func('cookieBanner');
    }
    echo htmlPage($pageData);
}
else{
    echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}
//file_put_contents("log.txt","success.php risposta => ".var_export($response,true)."\r\n");

/**
 * HTML page to show for non ajax requests
 * @param array $data
 * @return string
 */
function htmlPage(array $data): string{
    return <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Pagamento ordine</title>
        <meta charset="utf-8">
        {$data['cookieBanner']}
    </head>
    <body>
        {$data['menu']}
        <div class="mt-5 text-center fw-bold">{$data['message']}</div>
        {$data['footer']}
    </body>
</html>
HTML;
}
?>
