<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;

session_start();

require_once("vendor/autoload.php");

use EcommerceTest\Interfaces\Constants as C;


$ajax = (isset($_POST[C::KEY_AJAX]) && $_POST[C::KEY_AJAX] == '1');
$response = [
    C::KEY_MESSAGE => ''
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
                            $response[C::KEY_MESSAGE] = 'Pagamento effettuato con successo';
                        }
                        else{
                            $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                            $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                        } 
                    }
                    else{
                        $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                        $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                    }
                }//if($ordine->isCarrello() === true){
                else{
                    $response[C::KEY_MESSAGE] = 'Aggiungi al carrello il prodotto e riprova';
                }
            }
            catch(Exception $e){
                $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }
        }
        else{
            $response[C::KEY_MESSAGE] = 'Id ordine inesistente';
        } 
    }//if($_POST["payer_status"] == 'VERIFIED'){
}
else{
    if(!$ajax)$response[C::KEY_MESSAGE] = ACCEDI1;
    else $response[C::KEY_MESSAGE] = 'ERRORE: l\' utente è stato disconnesso';
}


if(!$ajax){
    $pageData = [
        'cookieBanner' => '',
        'menu' => menu($_SESSION['welcome']),
        'message' => $response[C::KEY_MESSAGE],
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
