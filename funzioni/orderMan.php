<?php

use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;

session_start();
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once('functions.php');
require_once('../objects/utente.php');
require_once('../objects/prodotto.php');
require_once('../objects/ordine.php');
require_once("const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $risposta = array();
    $p = array(); //array da passare alla classe prodotto
    $v = array(); //array da passare alla classe utente(venditore)
    $utente = unserialize($_SESSION['utente']);
    $nomeUtente = $utente->getUsername();
    if(isset($_GET['oper'])){
        //se l'utente richiede gli ordini che ha effettuato
        if($_GET['oper'] == '0'){
            $ordiniCliente = Ordine::getIdList($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb,$ordiniTable,$accountsTable,$nomeUtente);
            $risposta['i'] = count($ordiniCliente);
            $risposta['tab'] = '1'; //se verrà creata la tabella con gli ordini
            if($risposta['i'] > 0){
                $i = 0;
                foreach($ordiniCliente as $v){
                    try{
                        $ordine = new Ordine(array('id' => $v));
                        //var_dump($ordine->isCarrello());
                        if($ordine->getNumError() == 0){
                            $datiOrdine = array(
                                'id' => $ordine->getId(),
                                'idc' =>$ordine->getIdc(),
                                'idp' => $ordine->getIdp(),
                                'idv' => $ordine->getIdv(),
                                'data' => $ordine->getData(),
                                'quantita' => $ordine->getQuantita(),
                                'totale' => $ordine->getTotale(),
                                'pagato' => ($ordine->isPagato())? '1':'0',
                                'carrello' => ($ordine->isCarrello())? '1':'0',
                            );
                            
                            $risposta[$i]=$datiOrdine;
                            $_SESSION['ordini'][$ordine->getId()]=$datiOrdine;
                            $i++;
                        }//if($ordine->getNumError() == 0){
                        else{
                             $risposta['msg'] = $ordine->getStrError().'<br>';
                             $risposta['msg'] .= ' Linea n. '.__LINE__;
                        }
                    }
                    catch(Exception $e){
                        $risposta['msg'] = $e->getMessage();
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }
            }
        }//if($_GET['oper'] == '0'){
        //l'utente richiede i dettagli di un ordine specifico
        else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
            if(isset($_SESSION['ordini'][$_GET['idOrd']])){
                try{
                    $ordine = new Ordine(array('id' => $_SESSION['ordini'][$_GET['idOrd']]['id']));
                    if($ordine->getNumError() == 0){
                        $prodotto = new Prodotto(array('id' => $ordine->getIdp()));
                        if($prodotto->getNumError() == 0){
                            $vend = array();
                            $vend['id'] = $prodotto->getIdu();
                            $vend['registrato'] = '1';
                            $venditore = new Utente($vend);
                            if($venditore->getNumError() == 0 || $venditore->getNumError() == UTENTEERR_INCORRECTLOGINDATA){
                                $risposta['nomeP'] = $prodotto->getNome();
                                $risposta['tipo'] = $prodotto->getTipo();
                                $risposta['prezzo'] = sprintf("%.2f",$prodotto->getPrezzo());
                                $risposta['spedizione'] = sprintf("%.2f",$prodotto->getSpedizione());
                                $risposta['quantita'] = $ordine->getQuantita();
                                $risposta['stato'] = $prodotto->getStato();
                                $risposta['citta'] = $prodotto->getCitta();
                                $risposta['totale'] = sprintf("%.2f",$ordine->getTotale());
                                $risposta['nome'] = $venditore->getNome();
                                $risposta['cognome'] = $venditore->getCognome();
                                $risposta['nascita'] = $venditore->getNascita();
                                $risposta['indirizzo'] = $venditore->getIndirizzo();
                                $risposta['numero'] = $venditore->getNumero();
                                $risposta['citta'] = $venditore->getCitta();
                                $risposta['cap'] = $venditore->getCap();
                                $risposta['email'] = $venditore->getEmail();
                                $risposta['info'] = '1';
                            }
                            else{
                                $risposta['msg'] = $venditore->getStrError().'<br>';
                                //$risposta['msg'] .= ' Linea n. '.__LINE__;
                            }
                        }
                        else{
                            $risposta['msg'] = $prodotto->getStrError().'<br>';
                            //$risposta['msg'] .= ' Linea n. '.__LINE__;
                        }
                    }
                    else{
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        //$risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                    
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    //$risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        }//else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
        //l'utente vuole cancellare un determinato ordine
        else if($_GET['oper'] == '2' && isset($_GET['idOrd'])){
            if(isset($_SESSION['ordini'][$_GET['idOrd']])){
                //echo 'esiste ordini';
                try{
                    $ordine = new Ordine(array('id' => $_GET['idOrd']));
                    $ok = $ordine->cancOrdine($utente->getUsername());
                    if($ok){
                        $risposta['msg'] = 'Ordine cancellato con successo';
                        $risposta['aggiorna'] = '1';
                        unset($_SESSION['ordini']);
                    }
                    else{
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            //else echo 'non esiste';
            //$canc = cancOrdine($_GET['idOrd'],$_SESSION['user']);
        }
        //l'utente vuole aggiungere al carrello un ordine
         //l'utente vuole modificare la quantità di un ordine
         else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
            if(isset($_SESSION['ordini'][$_GET['idOrd']])){
                $quantita = $_GET['quantita'];
                if(is_numeric($quantita) && $quantita > 0){
                    try{
                        $ordine = new Ordine(array('id' => $_GET['idOrd']));
                        if($ordine->getNumError() == 0){
                            $idp = $ordine->getIdp();
                            $prodotto = new Prodotto(array('id' => $idp));
                            if($prodotto->getNumError() == 0){
                                $prezzo = $prodotto->getPrezzo();
                                $spedizione = $prodotto->getSpedizione();
                                $aQt = array();
                                $aQt['quantita'] = $quantita;
                                $aQt['totale'] = $quantita*($prezzo+$spedizione);
                                $aQt['totale'] = sprintf("%.2f",$aQt['totale']);
                                $update = $ordine->update($aQt);
                                if($update){
                                    $risposta['msg'] = 'Quantità ordine modificata con successo';
                                    $risposta['aggiorna'] = '1';
                                }
                                else{
                                    $risposta['msg'] = 'Errore durante l\' aggiornamento della quantità';
                                }
                            }
                            else{
                                $risposta['msg'] = $prodotto->getStrError().'<br>';
                                $risposta['msg'] .= ' Linea n. '.__LINE__;
                            }
                        }
                        else{
                            $risposta['msg'] = $ordine->getStrError().'<br>';
                            $risposta['msg'] .= ' Linea n. '.__LINE__;
                        }

                    }
                    catch(Exception $e){
                        $risposta['msg'] = $e->getMessage().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }
                else{
                    $risposta['msg'] = 'Quantità non valida';
                }
            }
            else{
                $risposta['msg'] = 'Ordine non valido';
            }
        }//else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
        //l utente vuole aggiungere al carrello un ordine
        else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
            if(isset($_SESSION['ordini'][$_GET['idOrd']])){
                try{
                    $ordine = new Ordine(array('id' => $_GET['idOrd']));
                    if($ordine->getNumError() == 0){
                        $carrello = $ordine->isCarrello();
                        if(!$carrello){
                            $aggiungi = $ordine->addToCart($utente->getUsername());
                            if($aggiungi){
                                $risposta['msg'] = 'Ordine inserito nel carrello';
                                $risposta['aggiorna'] = '1';
                            }
                            else{                           
                                $risposta['msg'] = $ordine->getStrError().'<br>';
                                $risposta['msg'] .= ' Linea n. '.__LINE__;
                            }
                        }
                        else $risposta['msg'] = 'Ordine già presente nel carrello';
                    }
                    else{
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            else{
                $risposta['msg'] = 'Ordine non valido';
            }
        }//else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
        //l'utente vuole pagare un ordine lasciato in sospeso
        else if($_GET['oper'] == '5' && isset($_GET['idOrd'])){
            if(isset($_SESSION['ordini'][$_GET['idOrd']])){
            }
            else{
                $risposta['msg'] = 'Ordine non valido';
            }

        }
        else{
            $risposta['msg'] = 'Operazione selezionata non valida';
        }
    }//if(isset($_GET['oper'])){
    else{
        $risposta['msg'] = 'Nessuna operazione selezionata';
    }
    echo json_encode($risposta,JSON_UNESCAPED_UNICODE);

}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI2;
}
?>