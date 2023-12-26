<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Objects\Utente;

/**
 * Trait used by Utente class for constructor logic
 */
trait UtenteConstructorTrait{

    /**
     * If user is not subscribed
     */
    private function ifGuest(array $ingresso){
        //utente che si registra
        if(isset($ingresso['email'],$ingresso['username'])){
            /*Verifica se esistono la mail paypal e l'ID del cliente e controlla che siano
            valori validi */
            if($this->valida($ingresso)){
                $where = "`email` = '{$ingresso['email']}'";
                $mailexists = $this->Exists($where);
                //controllo che il nome utente non esista già
                $where = "`username` = '{$ingresso['username']}'";
                $userexists = $this->Exists($where);
                if($mailexists == 0 && $userexists == 0){
                    //l'indirizzo email e lo username inserito è disponibile
                    $this->setCodAut($this->codAutGen('0'));
                    $ingresso['codAut'] = $this->getCodAut();
                    //$ingresso['registrato'] non deve essere inserito nel database
                    unset($ingresso['registrato']);
                    //se i dati sono stati inseriti nel database
                    if($this->insertData($ingresso)){
                        $this->pSetValues($ingresso);
                    }
                }//if($mailexists == 0 && $userexists == 0){
                //(registrazione) lo username o la mail inserita esistono già
                else $this->numError = Ue::USERNAMEMAILEXIST;
            }//if($this->valida($ingresso)){
            //Uno o più parametri non sono nel formato corretto
            else $this->numError = Ue::INVALIDDATAFORMAT;
        }//if(isset($ingresso['email'],$ingresso['username'])){
        //non esegue nessuna operazione dopo aver istanziato l'oggetto
        else{
            $this->numError = Ue::DATANOTSET; //uno o più dati richiesti non sono stati settati
        }
    }

    /**
     * If subscribe user is subscribed
     */
    private function ifSubscribed(array $ingresso){
        if(!isset($ingresso['campo']))$ingresso['campo'] = Utente::$campi[0];
        //$this->{ingresso['campo']} è il campo che verrà usato per verificare che i dati inseriti siano corretti
        if(in_array($ingresso['campo'],Utente::$campi) && isset($this->{$ingresso['campo']})){
            //espressione regolare codice di recupero
            //$regex = '/^[a-z0-9]{64}$/i';
            //password dimenticata(recupera.php)
            if(isset($ingresso['dimenticata']) && $ingresso['dimenticata']){
                if(isset($this->cambioPwd,$this->dataCambioPwd)){
                    $this->recupera($ingresso);
                }
                //se non esiste già il codice di recupero password, viene creato e poi in una mail il link per il ripristino
                else{
                    $this->cambioPwd = $this->codAutGen('1');
                    $this->dataCambioPwd = time();
                }
            }//if(isset($ingresso['dimenticata']) && $ingresso['dimenticata']){
            //l'utente deve completare la registrazione attivando l'account (attiva.php)
            else if(isset($ingresso['codAut']) && preg_match(Utente::$regex['codAut'],$ingresso['codAut'])){
                $this->setCodAut($ingresso['codAut']);
                if($this->attiva()){
                    $this->setCodAut(null);
                }
            }
            //tentativo di login dell'utente
            else{
                $dati = array();
                //$regex = '/^[a-zA-Z-_0-9]{4,20}@([a-z]{3,15}\.){1,6}[a-z]{2,10}$/';
                //l'utente vuole accedere alla sua area personale
                if(isset($ingresso[$ingresso['campo']]) && preg_match(Utente::$regex[$ingresso['campo']],$ingresso[$ingresso['campo']])){
                    $dati[$ingresso['campo']] = $ingresso[$ingresso['campo']];
                    $user = $this->getUserData($dati);
                    //la mail esiste nel database
                    if($user !== FALSE){
                        //inserisco i valori ottenuti dalla riga MySql nell'oggetto
                        $this->pSetValues($user);
                        //se la password è corretta
                        if(isset($ingresso['password']) && password_verify($ingresso['password'],$user['password'])){
                            $codiceAut = $this->getCodAut();
                            //se l'account è già stato attivato
                            if(is_null($codiceAut) || empty($codiceAut)){
                                $this->login = true;
                            }
                            //l'account non è ancora stato attivato
                            else $this->numError = Ue::ACTIVEYOURACCOUNT; //l'utente deve prima attivare l'account
                        }
                        else{
                            $this->numError = Ue::INCORRECTLOGINDATA; //email o password non corretti
                        }
                    }//if($user !== FALSE){ 
                    else{
                        $this->numError = Ue::INCORRECTLOGINDATA; //email o password non corretti
                    }
                }//if(isset($ingresso[$ingresso['campo']]) && preg_match(Utente::$regex[$ingresso['campo']],$ingresso[$ingresso['campo']])){
                else{
                    $this->numError = Ue::DATANOTSET; //uno o più dati richiesti non sono stati settati
                }
            }
        }//if(in_array($ingresso['campo'],Utente::$campi) && isset($this->{$ingresso['campo']})){
        //errore, uno o più campi richiesti non sono stati impostati
        else {
            $this->numError = Ue::INVALIDFIELD; //non è stato specificato un campo per fare la selezione dei dati oppure non è un campo valido
        }
    }

    /**
     * Set the inital values of Utente's class properties
     *  */
    private function setProperties(array $ingresso){
        $this->connesso = false;
        $this->querySql = null;
        $this->queries = array();
        $this->numError = 0;
        $this->strError = null;
        $this->login = false;
        $this->mysqlHost=isset($ingresso['mysqlHost'])? $ingresso['mysqlHost']:$_ENV['MYSQL_HOSTNAME'];
        $this->mysqlUser=isset($ingresso['mysqlUser'])? $ingresso['mysqlUser']:$_ENV['MYSQL_USERNAME'];
        $this->mysqlPass=isset($ingresso['mysqlPass'])? $ingresso['mysqlPass']:$_ENV['MYSQL_PASSWORD'];
        $this->h = new \mysqli($this->mysqlHost,$this->mysqlUser,$this->mysqlPass);
        if($this->h->connect_errno != 0){
            throw new \Exception(Ue::EXC_MYSQLCONN);
        }
        $this->connesso = true;
        $this->h->set_charset("utf8mb4");
        $this->mysqlDb=isset($ingresso['mysqlDb'])? $ingresso['mysqlDb']:$_ENV['MYSQL_DATABASE'];
        $this->createDb($this->mysqlDb); //crea il database se non esiste
        $this->mysqlTable=isset($ingresso['mysqlTable'])? $ingresso['mysqlTable']:$_ENV['TABACC'];
        if($this->createTab() === false){ //crea la tabella se non esiste
            throw new \Exception(Ue::EXC_TABLECREATION);
        }
        $this->id=isset($ingresso['id'])? $ingresso['id']:null;
        $this->nome=isset($ingresso['nome'])? $ingresso['nome']:null;
        $this->cognome=isset($ingresso['cognome'])? $ingresso['cognome']:null;
        $this->nascita=isset($ingresso['nascita'])? $ingresso['nascita']:null;
        $this->email=isset($ingresso['email'])? $ingresso['email']:null;
        $this->username=isset($ingresso['username'])? $ingresso['username']:null;
        $this->password=isset($ingresso['password'])? password_hash($ingresso['password'],PASSWORD_DEFAULT):'';
        $this->paypalMail=isset($ingresso['paypalMail'])? $ingresso['paypalMail']:null;
        $this->clientId=isset($ingresso['clientId'])? $ingresso['clientId']:null;
        $this->codAut=isset($ingresso['codAut'])? $ingresso['codAut']:null;
        $this->cambioPwd=isset($ingresso['cambioPwd'])? $ingresso['cambioPwd']: null;
        $this->dataCambioPwd=isset($ingresso['dataCambioPwd'])? $ingresso['dataCambioPwd']:null;
        $this->registrato=isset($ingresso['registrato'])? $ingresso['registrato']: false;

    }

}
?>