<?php
session_start();
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
        <link rel="stylesheet" href="css/utente.css" type="text/css">
        <link rel="stylesheet" href="css/inserzioni.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/inserzioni.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="menu">
                <div id="welcome"><?php echo $_SESSION['welcome']; ?></div>
                <div id="profilo">
                    Profilo
                    <div>
                        <a href="info.php">Informazioni</a>
                        <a href="edit.php">Modifica</a>
                    </div>
                </div>
                <div id="ordini">
                    Ordini
                    <div>
                        <a href="ordini.php">I miei ordini</a>
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>
                <div id="prodotto">
                    Prodotto
                    <div>
                        <a href="benvenuto.php">Cerca</a>
                        <a href="crea.php">Crea inserzione</a>
                        <a href="inserzioni.php">Le mie inserzioni</a>
                    </div>
                </div>
                <div id="contatti"><a href="contatti.php">Contatti</a></div>
                <div id="logout"><a href="funzioni/logout.php">Esci</a></div>
            </div>
        </div>
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