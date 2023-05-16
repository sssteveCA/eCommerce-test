<?php
//require_once('../objects/utente.php');
session_start();
unset($_SESSION['logged']);
unset($_SESSION['welcome']);
unset($_SESSION['utente']);
session_destroy();
header('location: /');
?>