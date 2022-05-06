<?php

use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
require_once('navbar.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/mysqlVals.php');
require_once('objects/prodotto.php');
require_once('objects/utente.php');
require_once('funzioni/config.php');
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Le mie inserzioni</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_INSERTIONS_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>></script>
        <script src=<?php echo P::REL_INSERTIONS_JS; ?>></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="risultato">
<?php
    //ottengo gli id dei prodotti che l'utente ha caricato
    $idA = $utente->getId();
    $query = <<<SQL
SELECT `id` FROM `prodotti` WHERE `idU`= '{$idA} ORDER BY `data` DESC LIMIT 30;';
SQL;
    echo '<script>console.log("'.$query.'");</script>';
    $listaId = Prodotto::getIdList($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb,$query);
    if($listaId !== null){
        if(!empty($listaId)){
            $prodotti = array();
            echo '<table border="1">';
            foreach($listaId as $id){
                try{
                    $prodotti[$id] = new Prodotto(array('id' => $id));
                    echo '<tr>';
                    //nome del prodotto
                    echo '<td class="nome">'.$prodotti[$id]->getNome().'</td>';
                    //immagine prodotto, mostrata con la codifica base64
                    echo '<td class="timg"><img src="'.$prodotti[$id]->getImmagine().'"></td>';
                    //categoria del prodotto
                    echo '<td class="tipo">'.$prodotti[$id]->getTipo().'</td>';
                    //prezzo in Euro
                    echo '<td class="prezzo">';
                    printf("%.2f€",$prodotti[$id]->getPrezzo());
                    echo '</td>';
                    echo '<td class="dettagli">';
                    //form che consente all'utente di aprire la scheda del prodotto selezionato
                    echo '<form method="get" action="prodotto.php"><input type="hidden" name="id" value="'.$id.'">';
                    echo '<input type="submit" value="DETTAGLI"></form>';
                    echo '</td>';
                    echo '</tr>';

                }
                catch(Exception $e){
                    echo '<script>alert("'.$e->getMessage().'");<br>';            
                }
            }
            echo '</table>';
        }
        else{
            echo '<p id="null">Non hai caricato alcun annuncio</p>';
        }
    }
    else{
        echo '<script>alert("Connessione a MySql fallita");</script>';
    }
?>
        </div>
    </body>
</html>
<?php
}
?>