<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Interfaces\ProductErrors as Pe;
use EcommerceTest\Interfaces\ProductVals as Pv;

trait ProdottoTrait{

    /**
     * Create the table if not exists
     */
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
CREATE TABLE `ordini` (
`id` int(4) NOT NULL AUTO_INCREMENT,
`idc` int(4) NOT NULL,
`idp` int(4) NOT NULL,
`idv` int(11) NOT NULL COMMENT 'ID del venditore',
`data` datetime NOT NULL,
`quantita` tinyint(4) NOT NULL,
`totale` float(30,2) unsigned NOT NULL,
`pagato` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'se il pagamento dell''ordine è andato a buon fine',
`tnx_id` varchar(300) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Id della transazione',
`carrello` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Se l''ordine è stato aggiunto al carrello',
PRIMARY KEY (`id`),
KEY `idc` (`idc`),
KEY `idp` (`idp`),
KEY `idv` (`idv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL;
                $this->queries[] = $this->querySql;
                $this->h->query($this->querySql);
            }//if($show->num_rows == 1){
        }//if($show){  
        else{
            $this->numError = Pe::QUERYERROR;
            $ok = false;
        }  
        return $ok;

    }

    /**
     * Change the date format from year-month-day to day-month-year
     */
    private function ddmmyyyy(){
        $dataArray = explode('-',$this->data);
        $dataArray2 = array($dataArray[2],$dataArray[1],$dataArray[0]);
        $data2 = implode('-',$dataArray2);
        return $data2;
    }

    /**
     * Set the inital values of Prodotto's class properties
     *  */
    private function setProperties(array $ingresso){
        $this->connesso = false;
        $mysqlHost=isset($ingresso['mysqlHost'])? $ingresso['mysqlHost']:$_ENV['MYSQL_HOSTNAME'];
        $mysqlUser=isset($ingresso['mysqlUser'])? $ingresso['mysqlUser']:$_ENV['MYSQL_USERNAME'];
        $mysqlPass=isset($ingresso['mysqlPass'])? $ingresso['mysqlPass']:$_ENV['MYSQL_PASSWORD'];
        $mysqlDb=isset($ingresso['mysqlDb'])? $ingresso['mysqlDb']:$_ENV['MYSQL_DATABASE'];
        $this->mysqlTable=isset($ingresso['mysqlTable'])? $ingresso['mysqlTable']:$_ENV['TABPROD'];
        $this->h = new \mysqli($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb);
        if($this->h->connect_errno !== 0){
            throw new \Exception("Connessione a MySql fallita: ".$this->h->connect_error);
        }
        $this->h->set_charset("utf8mb4");
        $this->connesso = true;
        $this->createDb($mysqlDb);
        if($this->createTab() === false){
            throw new \Exception(Pe::EXC_TABLECREATION);
        }
        $this->id=isset($ingresso['id'])? $ingresso['id']:null;
        $this->imgTmpName = null;
        $this->querySql = '';
        $this->numError = 0;
        $this->strError = null;
    }

    /**
     * Check the data format before insertion
     */
    private function valida($ingresso){
        $ok = true;
        $classname = __CLASS__;
        $condizione = array("Nuovo","Usato","Non specificato");
        if(!in_array($ingresso['tipo'],Pv::CATEGORIES)){
            $ok = false;
        }
        if(!is_numeric($ingresso['prezzo'])){
            $ok = false;
        }
        if(isset($ingresso['spedizione']) && !is_numeric($ingresso['spedizione'])){
            $ok = false;
        }
        if(!in_array($ingresso['condizione'],$condizione)){
            $ok = false;
        }
        if(isset($ingresso['data']) && preg_match($classname::$regex['data'],$ingresso['data'])){
            $dataA = explode('-',$ingresso['data']);
            if(!checkdate($dataA[1],$dataA[2],$dataA[0])){
                $ok = false;
            }
        }
        //else $ok = false;
        if($ok){
            $this->nome=isset($ingresso['nome'])? $ingresso['nome']:'Sconosciuto';
            $this->idU=isset($ingresso['idU'])? $ingresso['idU']:null;
            $this->immagine=isset($ingresso['immagine'])? $ingresso['immagine']:null;
            $this->stato=isset($ingresso['stato'])? $ingresso['stato']:'';
            $this->citta=isset($ingresso['citta'])? $ingresso['citta']:'';
            $this->descrizione=isset($ingresso['descrizione'])? $ingresso['descrizione']:'';
            $this->tipo=isset($ingresso['tipo'])? $ingresso['tipo']:'Altro';
            $this->prezzo=isset($ingresso['prezzo'])? $ingresso['prezzo']:'0';
            $this->spedizione=isset($ingresso['spedizione'])? $ingresso['spedizione']:'0';
            $this->condizione=isset($ingresso['condizione'])? $ingresso['condizione']:'Non specificato';
            $this->data=isset($ingresso['data'])? $ingresso['data']:date('Y-m-d');
        }
        return $ok;
    }
}
?>