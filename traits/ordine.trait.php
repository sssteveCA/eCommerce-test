<?php

namespace EcommerceTest\Traits;

use EcommerceTest\interfaces\OrderErrors as Oe;

trait OrdineTrait{

    /**
     * Create the table if not exists
     */
    private function createTab(){
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
            $this->numError = Oe::QUERYERROR;
            $ok = false;
        }  
        return $ok;
    }

    /**
     * Validate the input data before insertion
     */
    private function valida($ingresso){
        $ok = true;
        if(!isset($ingresso['idc']) || !is_numeric($ingresso['idc']))$ok = false;
        if(!isset($ingresso['idp']) || !is_numeric($ingresso['idp']))$ok = false;
        if(!isset($ingresso['idv']) || !is_numeric($ingresso['idv']))$ok = false;
        if(!isset($ingresso['quantita']) || !is_numeric($ingresso['quantita']))$ok = false;
        if(!isset($ingresso['totale']) || !is_numeric($ingresso['totale']))$ok = false;
        return $ok;
    }
}
?>