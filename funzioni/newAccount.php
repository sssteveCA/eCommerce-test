<?php

use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Messages as Msg;

require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('functions.php');
require_once('../objects/utente.php');
ob_start();

$data = json_decode((file_get_contents('php://input')));
$response = array();
$response['msg'] = 'Ciao';
$response['data'] = $data;

echo json_encode($response,JSON_UNESCAPED_UNICODE);
?>