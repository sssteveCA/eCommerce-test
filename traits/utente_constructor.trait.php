<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Interfaces\UserErrors as Ue;

/**
 * Trait used by Utente class for constructor logic
 */
trait UtenteConstructorTrait{

    /**Set the inital values of Utente's class properties */
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