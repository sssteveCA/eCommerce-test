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
require_once('funzioni/paypalConfig.php');
require_once('funzioni/const.php');

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ricerca</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_SEARCH_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_DIALOG_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="risultato">
<?php
    $clausole = false; //true se le clausole di WHERE sono più di una
    $query = "SELECT `id` FROM `prodotti` ";
    $idC = $utente->getId();
    $query .= "WHERE `idU` <> '{$idC}' ";
    $clausole = true;
    //parola chiave
    if(isset($_GET['ricerca']) && $_GET['ricerca'] != ''){
        $ricerca = $_GET['ricerca'];
        $query .= "AND "; 
        //verifico se nel nome è compreso '$ricerca'
        $query .= "`nome` REGEXP '$ricerca' ";
    }
    //categoria
    if(isset($_GET['selCat']) && $_GET['selCat'] != '' && $_GET['selCat'] != 'Tutte le categorie'){
        //ottengo il value dell' <option> selezionato
        $tipo = $_GET['selCat'];
        $query .= "AND ";
        $query .= "`tipo` = '$tipo' ";
    }
    //prezzo
    if(isset($_GET['cPrezzo']) && $_GET['cPrezzo'] == '1'){
        //prezzo minimo
        if(isset($_GET['minP'])){
            if(is_numeric($_GET['minP'])){
                $minP = $_GET['minP'];
                $query .= "AND ";
                $query .= "`prezzo` >= '$minP' ";
            }
            else exit("Il prezzo minimo inserito non è valido");
        }
        //prezzo massimo
        if(isset($_GET['maxP'])){
            if(is_numeric($_GET['maxP'])){
                $maxP = $_GET['maxP'];
                $query .= "AND ";
                $query .= "`prezzo` <= '$maxP' ";  
            }
            else exit("Il prezzo massimo inserito non è valido");
        }
    }
    //condizione
    $nuovo = false;
    $usato = false;
    $nonSp = false;
    //nuovo
    if(isset($_GET['cN']) && $_GET['cN'] == '1'){
        $nuovo = true;
    }
    //usato
    if(isset($_GET['cU']) && $_GET['cU'] == '1'){
        $usato = true;
    }
    //non specificato
    if(isset($_GET['cNs']) && $_GET['cNs'] == '1'){
        $nonSp = true;
    }
    /*se è stata specificata almeno una delle 3 condizioni, inserisco la parola chiave IN*/
    if($nuovo || $usato || $nonSp){
        $query .= "AND ";
        $query .= "`condizione` IN (";
        if($nuovo)$query .= "'Nuovo' ";
        if($usato){
            if($nuovo)$query .= ", ";
            $query .= "'Usato' ";
        }
        /*se prima di 'non specificato' ci sono 'nuovo' o 'usato' inserisco una virgola
        prima della stringa vuota */
        if($nonSp){
            if($nuovo || $usato)$query .= ", ";
            $query .= "'' ";
        }
        $query .= ") ";
        if($nonSp)$query .= "OR `condizione` IS NULL "; 
    }
    //data più vecchia
    if(isset($_GET['dataI'])){
        if(isset($_GET['oDate']) && $_GET['oDate'] != ''){
            $dateI = explode('-',$_GET['oDate']);
            //verifico se la data più vecchia è valida
            if(checkdate($dateI[1],$dateI[2],$dateI[0])){
                $dataI = $_GET['oDate'];
                $query .= "AND ";
                $query .= "`data` >= '$dataI' ";
            }
            else exit("Data più vecchia non valida");
        }
    }
    //data più recente
    if(isset($_GET['dataF'])){
        if(isset($_GET['rDate']) && $_GET['rDate'] != ''){
            $dateF = explode('-',$_GET['rDate']);
            //verifico se la data più recente è valida
            if(checkdate($dateF[1],$dateF[2],$dateF[0])){
                $dataF = $_GET['rDate'];
                $query .= "AND ";
                $query .= "`data` <= '$dataF' ";
            }
            else exit("Data più vecchia non valida");
        }
    }
    //nazione di provenienza
    if(isset($_GET['cStato']) && $_GET['cStato'] == '1'){
        if(isset($_GET['stato']) && $_GET['stato'] != ''){
            $stato = $_GET['stato'];
            $query .= "AND ";
            $query .= "`stato` = '$stato' ";
        }
    }
    //città di provenienza
    if(isset($_GET['cCitta']) && $_GET['cCitta'] == '1'){
        if(isset($_GET['citta']) && $_GET['citta'] != ''){
            $citta = $_GET['citta'];
            $query .= "AND ";
            $query .= "`citta` = '$citta' ";
        }
    }
    //i prodotti ottenuti sono mostrati in ordine decrescente in base alla data in cui è stato pubblicato l'annuncio
    $query .= "ORDER BY `data` DESC LIMIT 30;";
    //id dei prodotti ottenuti dalla query di ricerca
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
                    echo $e->getMessage().'<br>';  
                    echo ' Linea n. '.__LINE__;          
                }
            }
            echo '</table>';
        }
        else{
            echo '<p id="null">La ricerca non ha prodotto alcun risultato</p>';
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
else{
    echo ACCEDI1;
}
?>