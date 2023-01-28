<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Interfaces\UserErrors as Ue;

trait UtenteTrait{

    /**
     * Activate the account after registration
     */
    private function attiva(){
        $ok = false;
        $this->numError = 0;
        $codAut = $this->codAut;
        $this->querySql = <<<SQL
UPDATE `accounts` SET `codAut` = NULL WHERE `codAut` = '{$codAut}';
SQL;
        $this->queries[] = $this->querySql;
        if($this->h->query($this->querySql) !== FALSE){
            if($this->h->affected_rows == 1){
                $ok = true;
            }
            else $this->numError = Ue::ACCOUNTNOTACTIVATED; //attivazione account non riuscita
        }//if($h->query($query) !== FALSE){
        else $this->numError = Ue::QUERYERROR; //query errata
        return $ok;
    }
    //crea il codice di attivazione o di recupero password dell'account 
    public function codAutGen($ordine){
        $codAut = str_replace('.','a',microtime());
        $codAut = str_replace(' ','b',$codAut);
        $lCod = strlen($codAut);
        $lCas = 64 - $lCod;
        $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYzabcdefghijklmnopqrstuvwxyz0123456789';
        $lc = strlen($c) - 1;
        $s = '';
        for($i = 0; $i < $lCas; $i++)
        {
            $j = mt_rand(0,$lc);
            $s .= $c[$j];
        }
        if($ordine == '0') return $codAut.$s;
        else return $s.$codAut;
    }

    private function createTab(){
        //crea la tabella se non esiste
        $ok = true;
        $this->numError = 0;
        $this->querySql = <<<SQL
SHOW TABLES LIKE '{$this->mysqlTable}'; 
SQL;
        $this->queries[] = $this->querySql;
        $show = $this->h->query($this->querySql);
        if($show){
            if($show->num_rows == 0){
                //se la tabella non esiste viene creata
                $this->querySql = <<<SQL
CREATE TABLE `accounts` (
`id` smallint(6) NOT NULL AUTO_INCREMENT,
`nome` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
`cognome` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
`nascita` date NOT NULL,
`sesso` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
`indirizzo` varchar(100) CHARACTER SET utf8mb4 NOT NULL COMMENT 'via/piazza/Loc.',
`numero` varchar(8) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'numero civico',
`citta` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT 'città di residenza',
`cap` varchar(10) CHARACTER SET utf8mb4 NOT NULL COMMENT 'codice di avviamento postale',
`email` varchar(40) CHARACTER SET utf8mb4 NOT NULL,
`username` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
`password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
`paypalMail` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
`clientId` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'ID del venditore Paypal',
`codAut` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
`cambioPwd` varchar(64) CHARACTER SET utf8mb4 DEFAULT NULL,
`dataCambioPwd` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `email` (`email`),
UNIQUE KEY `username` (`username`),
UNIQUE KEY `codAut` (`codAut`),
UNIQUE KEY `cambioPwd` (`cambioPwd`) USING BTREE,
UNIQUE KEY `ClientId` (`clientId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL;
                $this->queries[] = $this->querySql;
                $this->h->query($this->querySql);
            }//if($show->num_rows == 1){
        }//if(!$show){  
        else{
            $this->numError = Ue::QUERYERROR;
            $ok = false;
        }  
        return $ok;

    }

    //trasforma una data nel formato anno-mese-giorno in giorno-mese-anno
    private function ddmmyyyy($data){
        $dataArray = explode('-',$data);
        $dataArray2 = array($dataArray[2],$dataArray[1],$dataArray[0]);
        $data2 = implode('-',$dataArray2);
        return $data2;
    }

    /**
     * Check if a column contains a row that has a specific value
     */
    public function Exists($where){
        $this->numError = 0;
        $this->querySql = <<<SQL
SELECT * FROM `{$this->mysqlTable}` WHERE {$where};
SQL;
        $this->queries[] = $this->querySql;
        $r = $this->h->query($this->querySql);
        if($r){
            if($r->num_rows > 0){
                $ret = 1; //il valore indicato nel campo specificato esiste già
            }
            else $ret = 0;
        }
        else{
            $ret = -1;
            $this->numError = Ue::QUERYERROR; //query errata
        }
        return $ret;
    }

    /**
     * Insert the value of the input in the proper properties
     */
    private function pSetValues($ingresso){
        $this->id=isset($ingresso['id'])? $ingresso['id']:null;
        $this->nome=isset($ingresso['nome'])? $ingresso['nome']:'';
        $this->cognome=isset($ingresso['cognome'])? $ingresso['cognome']:'';
        $this->sesso=isset($ingresso['sesso'])? $ingresso['sesso']:'Maschio';
        $this->nascita=isset($ingresso['nascita'])? $ingresso['nascita']:'';
        $this->indirizzo=isset($ingresso['indirizzo'])? $ingresso['indirizzo']:'';
        $this->numero=isset($ingresso['numero'])? $ingresso['numero']:'';
        $this->citta=isset($ingresso['citta'])? $ingresso['citta']:'';
        $this->cap=isset($ingresso['cap'])? $ingresso['cap']:'';
        $this->email=isset($ingresso['email'])? $ingresso['email']:null;
        $this->paypalMail=isset($ingresso['paypalMail'])? $ingresso['paypalMail']:null;
        $this->clientId=isset($ingresso['clientId'])? $ingresso['clientId']:null;
        $this->username=isset($ingresso['username'])? $ingresso['username']:null;
        if($this->registrato)$this->password=isset($ingresso['password'])? $ingresso['password']:'';
        else $this->password=isset($ingresso['password'])? password_hash($ingresso['password'],PASSWORD_DEFAULT):'';
        $this->codAut=isset($ingresso['codAut'])? $ingresso['codAut']: null;
        $this->cambioPwd=isset($ingresso['cambioPwd'])? $ingresso['cambioPwd']: null;
        $this->dataCambioPwd=isset($ingresso['dataCambioPwd'])? $ingresso['dataCambioPwd']:null;
    }

    /**
     * Recovery the account by resetting the password
     */
    private function recupera($ingresso){
        $this->numError = 0;
        $ok = false;
        if(isset($ingresso['nuovaP']) && $ingresso['nuovaP'] != ''){
            $this->h->set_charset("utf8mb4");
            $nuovaC = password_hash($ingresso['nuovaP'],PASSWORD_DEFAULT);
            $this->querySql = <<<SQL
UPDATE `accounts` SET `dataCambioPwd` = NULL, `cambioPwd` = NULL, `password` = '{$nuovaC}'
WHERE `cambioPwd` = '{$this->cambioPwd}' AND `dataCambioPwd` >= '{$this->dataCambioPwd}';
SQL;
            $this->queries[] = $this->querySql;
            if($this->h->query($this->querySql) !== FALSE){
                if($this->h->affected_rows == 1){
                    $ok = true;
                }
                else $this->numError = Ue::ACCOUNTNOTRECOVERED; //impossibile recuperare l'account
            }
            else $this->numError = Ue::QUERYERROR; //query errata
        }//if(isset($ingresso['nuovaP']) && $ingresso['nuovaP'] != ''){
        else $this->numError = Ue::DATANOTSET; //uno o più dati richiesti non sono stati settati
        return $ok;
    }
    

    /**
     * Insert the value of the input in the proper properties (updating the existing properties)
     */
    private function setValues($ingresso){
        $ok = true;
        $this->nome=isset($ingresso['nome'])? $ingresso['nome']:$this->nome;
        $this->cognome=isset($ingresso['cognome'])? $ingresso['cognome']:$this->cognome;
        $this->indirizzo=isset($ingresso['indirizzo'])? $ingresso['indirizzo']:$this->indirizzo;
        $this->numero=isset($ingresso['numero'])? $ingresso['numero']:$this->numero;
        $this->citta=isset($ingresso['citta'])? $ingresso['citta']:$this->citta;
        $this->cap=isset($ingresso['cap'])? $ingresso['cap']:$this->cap;
        $this->username=isset($ingresso['username'])? $ingresso['username']:$this->username;
        $this->password=isset($ingresso['password'])? password_hash($ingresso['password'],PASSWORD_DEFAULT):$this->password;
        $this->paypalMail=isset($ingresso['paypalMail'])? $ingresso['paypalMail']:$this->password;
        $this->clientId=isset($ingresso['clientId'])? $ingresso['clientId']:$this->password;
        $this->codAut=isset($ingresso['codAut'])? $ingresso['codAut']: $this->codAut;
        $this->cambioPwd=isset($ingresso['cambioPwd'])? $ingresso['cambioPwd']: $this->cambioPwd;
        $this->dataCambioPwd=isset($ingresso['dataCambioPwd'])? $ingresso['dataCambioPwd']:$this->dataCambioPwd;
        return $ok;
    }
    
    /**
     * Validate the input data before the insertion
     */
    public function valida($ingresso){
        $ok = true;
        $classname = __CLASS__;
        $this->errno = 0;
        if(!preg_match($classname::$regex['email'],$this->email))$ok = false;
        //file_put_contents("log.txt","utente.php ok email => ".var_export($ok,true)."\r\n",FILE_APPEND);
        if(isset($ingresso['paypalMail']) && $ingresso['paypalMail'] != "" && !preg_match($classname::$regex['paypalMail'],$ingresso['paypalMail']))$ok = false;
        //file_put_contents("log.txt","utente.php ok paypal => ".var_export($ok,true)."\r\n",FILE_APPEND);
        if(isset($ingresso['clientId']) && $ingresso['clientId'] != "" && !preg_match($classname::$regex['clientId'],$ingresso['clientId']))$ok = false;
        //file_put_contents("log.txt","utente.php ok client => ".var_export($ok,true)."\r\n",FILE_APPEND);
        if(!preg_match($classname::$regex['username'],$this->username))$ok = false;
        //file_put_contents("log.txt","utente.php ok username => ".var_export($ok,true)."\r\n",FILE_APPEND);
        if(!$ok)$this->errno = Ue::INVALIDDATAFORMAT;
        return $ok;
    }
    
}
?>