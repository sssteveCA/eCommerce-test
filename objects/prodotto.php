<?php

namespace EcommerceTest\Objects;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\ProductErrors as Pe;
use EcommerceTest\Interfaces\ProductVals as Pv;
use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Config as Cf;

define("PRODOTTOERR_INFONOTGETTED","1");
define("PRODOTTOERR_IMGNOTCOPIED","2");
define("PRODOTTOERR_QUERYERROR","3");
define("PRODOTTOERR_DATANOTDELETED","4");
define("PRODOTTOERR_DATANOTINSERTED","5");
define("PRODOTTOERR_IDNOTEXIST","6");

class Prodotto implements Pe,Pv,Mv{
    private $h;
    public $connesso;
    private $mysqlTable;
    private $id;
    private $idU; //ID dell'utente che ha caricato l'annuncio
    private $nome;
    private $immagine; //percorso immagine del prodotto
    private $imgTmpName; //percorso temporaneo dell'immagine caricata
    private $tipo; //categoria prodotto
    private $prezzo; //prezzo in euro
    private $spedizione; //spese di spedizione
    private $condizione;
    private $stato; //stato di provenienza
    private $citta; //città da cui parte l'ordine
    private $data; //data inserimento inserzione
    private $descrizione;
    private $querySql;
    private $queries;
    private $numError;
    private $strError;
    private static $regex = array(
        'data' => '/^\d{4}-\d{1,2}-\d{1,2}$/'
    );
    //contiene la lista degli id ottenuti dalla query di ricerca
    private static $idList = array(); 
    public function __construct($ingresso){
        $dotenv = Dotenv::createImmutable(__DIR__."../");
        $dotenv->safeLoad();
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
        $this->createDb();
        if($this->createTab() === false){
            throw new \Exception(Pe::EXC_TABLECREATION);
        }
        $this->id=isset($ingresso['id'])? $ingresso['id']:null;
        $this->imgTmpName = null;
        $this->querySql = '';
        $this->numError = 0;
        $this->strError = null;

        //caricamento di un prodotto 
        if(isset($this->id)){
            $ok = $this->getProduct();
            if($ok)$this->numError = 0;
            else $this->numError = Pe::INFONOTGETTED; //impossibile ottenere le informazioni sul prodotto dal database MySql
        }
        //inserimento di un nuovo prodotto
        else{
            if($this->valida($ingresso)){
                $this->imgTmpName = $ingresso['tmp_name'];
                $this->insertProduct();
            }
            else throw New \Exception(Pe::EXC_INVALIDDATA);
        }

    }

    //vuole un'array come risultato
    public function __serialize() {
        //echo 'serialize<br>';
        return [
            'id' => $this->id,
            'idU' => $this->idU,
            'nome' => $this->nome,
            'immagine' => $this->immagine,
            'tipo' => $this->tipo,
            'prezzo' => $this->prezzo,
            'spedizione' => $this->spedizione,
            'condizione' => $this->condizione,
            'stato' => $this->stato,
            'citta' => $this->citta,
            'data' => $this->data,
            'descrizione' => $this->descrizione
        ];
    }
    
    public function __unserialize(array $data) {
        $this->id = $data['id'];
        $this->idU = $data['idU'];
        $this->nome = $data['nome'];
        $this->immagine = $data['immagine'];
        $this->tipo = $data['tipo'];
        $this->prezzo = $data['prezzo'];
        $this->spedizione = $data['spedizione'];
        $this->condizione = $data['condizione'];
        $this->stato = $data['stato'];
        $this->citta = $data['citta'];
        $this->data = $data['data'];
        $this->descrizione = $data['descrizione'];
        //var_dump($data);
        //echo 'unserialize<br>';
        $this->connesso=false;
    }

    public function __destruct(){
        if($this->connesso) {
            $this->h->close();
        }
    }
    public function __toString(){
        return $this->nome;
    }
    public function __get($prop){

    }
    public function __set($prop, $val){

    }
    
    //ottengo l'id del prodotto dalla lettura della tabella MySql
    public function getId(){return $this->id;}
    public function getIdu(){return $this->idU;}
    public function getNome(){return $this->nome;}
    public function getImmagine(){return $this->immagine;}
    public function getTipo(){return $this->tipo;}
    public function getPrezzo(){return $this->prezzo;}
    public function getSpedizione(){return $this->spedizione;}
    public function getCondizione(){return $this->condizione;}
    public function getStato(){return $this->stato;}
    public function getCitta(){return $this->citta;}
    //data nel formato giorno-mese-anno
    public function getData(){return $this->ddmmyyyy($this->data);}
    public function getDescrizione(){return $this->descrizione;}
    public function getQuery(){return $this->querySql;}
    public function getQueries(){return $this->queries;}
    /*
    0 = nessun errore
    1 = Impossibile ottenere le informazioni sul prodotto dal database MySql
     */
    public function getNumError(){return $this->numError;}
    public function getStrError(){
        switch($this->numError){
            case 0:
                $this->strError = null;
                break;
            case Pe::INFONOTGETTED:
                $this->strError = Pe::MSG_INFONOTGETTED;
                break;
            case Pe::IMGNOTCOPIED:
                $this->strError = Pe::MSG_IMGNOTCOPIED;
                break;
            case Pe::QUERYERROR:
                $this->strError = Pe::MSG_QUERYERROR;
                break;
            case Pe::DATANOTDELETED:
                $this->strError = Pe::MSG_DATANOTDELETED;
                break; 
            case Pe::DATANOTINSERTED:
                $this->strError = Pe::MSG_DATANOTINSERTED;  
                break;
            case Pe::IDNOTEXIST:
                $this->strError = Pe::MSG_IDNOTEXIST;
                break;
            default:
                $this->strError = null;
                break;
        }
        return $this->strError;
    }

    private function createDb(){
        $ok = true;
        $this->numError = 0;
        $this->querySql = <<<SQL
CREATE DATABASE IF NOT EXISTS `{$this->mysqlDb}`;
SQL;
        $this->queries[] = $this->querySql;
        $this->h->query($this->querySql);
        $this->h->select_db($this->mysqlDb);
        return $ok;
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
     
    public function cancella($idUtente){
        $this->numError = 0;
        $ok = false;
        if(isset($this->id)){
            $this->querySql = <<<SQL
DELETE FROM `{$this->mysqlTable}` WHERE `id` = '{$this->id}' AND `idU` = '$idUtente';
SQL;
            $this->queries[] = $this->querySql;
            if($this->h->query($this->querySql) === TRUE){
                if($this->h->affected_rows == 1){
                    $ok = true;
                }
                else
                    $this->numError = Pe::DATANOTDELETED;
            }
            else
                $this->numError = Pe::QUERYERROR; 
        }//if(isset($this->id)){
        else 
            $this->numError = Pe::IDNOTEXIST;
        return $ok;
    }
    //trasforma una data nel formato anno-mese-giorno in giorno-mese-anno
    private function ddmmyyyy(){
        $dataArray = explode('-',$this->data);
        $dataArray2 = array($dataArray[2],$dataArray[1],$dataArray[0]);
        $data2 = implode('-',$dataArray2);
        return $data2;
    }

    //ottengo la lista degli id ottenuti dalla query di ricerca per la tabella prodotti
    public static function getIdList($host,$username,$password,$database,$query){
        Prodotto::$idList = array();
        $handle = new \mysqli($host,$username,$password,$database);
        if($handle->connect_errno === 0){
            $handle->set_charset("utf8mb4");
            //$queryE = $handle->real_escape_string($query);
            $r = $handle->query($query);
            if($r){
                if($r->num_rows > 0){
                    while($indice =  $r->fetch_array(MYSQLI_ASSOC)){
                        Prodotto::$idList[] = $indice['id']; 
                    }
                }
                $r->free();
            }
            $handle->close();
        }
        else Prodotto::$idList = null; //connessione a mysql fallita
        return Prodotto::$idList;
    }

    private function getProduct(){
        $ok = false;
        if(isset($this->id)){
            $this->querySql = <<<SQL
SELECT * FROM `{$this->mysqlTable}` WHERE `id` = '{$this->id}';
SQL;
            $this->queries[] = $this->querySql;
            $r = $this->h->query($this->querySql);
            if($r){
                if($r->num_rows == 1){
                    $ok = true;
                    $prodotto = $r->fetch_array(MYSQLI_ASSOC);
                    $this->nome = $prodotto['nome'];
                    $this->idU = $prodotto['idU'];
                    $this->immagine = $prodotto['immagine'];
                    $this->tipo = $prodotto['tipo'];
                    $this->prezzo = $prodotto['prezzo'];
                    $this->spedizione = $prodotto['spedizione'];
                    $this->condizione = $prodotto['condizione'];
                    $this->stato = $prodotto['stato'];
                    $this->citta = $prodotto['citta'];
                    $this->data = $prodotto['data'];
                    $this->descrizione = $prodotto['descrizione'];
                }
                $r->free();
            }
        }
        return $ok;
    }

    private function insertProduct(){
        $this->numError = 0;
        $ok = false;
        $nomeE = $this->h->real_escape_string($this->nome);
        $statoE = $this->h->real_escape_string($this->stato);
        $cittaE = $this->h->real_escape_string($this->citta);
        $descrizioneE = $this->h->real_escape_string($this->descrizione);
        if(isset($this->immagine)){
        $this->querySql = <<<SQL
INSERT INTO `{$this->mysqlTable}`(`idU`,`nome`, `tipo`, `prezzo`, `spedizione`, `condizione`, `stato`, `citta`, `data`, `descrizione`,`immagine`) 
VALUES (
'{$this->idU}','$nomeE','{$this->tipo}','{$this->prezzo}','{$this->spedizione}','{$this->condizione}','$statoE','$cittaE','{$this->data}','$descrizioneE',
'{$this->immagine}');
SQL;
        }//if(isset($this->immagine)){
        else{
            $this->querySql = <<<SQL
INSERT INTO `{$this->mysqlTable}`(`idU`,`nome`, `tipo`, `prezzo`,  `spedizione`, `condizione`, `stato`, `citta`, `data`, `descrizione`,`immagine`) 
VALUES (
        '{$this->idU}','$nomeE','{$this->tipo}','{$this->prezzo}','{$this->spedizione}','{$this->condizione}','$statoE','$cittaE','{$this->data}','$descrizioneE',
        CONCAT('img/',
        (SELECT `AUTO_INCREMENT`
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'stefano'
        AND   TABLE_NAME   = 'prodotti'),
        '.jpg')
    );
SQL;
        }//else di if(isset($this->immagine)){
        $this->queries[] = $this->querySql;
        if($this->h->query($this->querySql) !== FALSE){
            if($this->h->affected_rows > 0){
                $this->id = $this->h->insert_id;
                if($this->id > 0){
                    if(copy($this->imgTmpName,"../img/{$this->id}.jpg")){
                        $ok = true;
                    }
                    else $this->numError = Pe::IMGNOTCOPIED; //Il file immagine non è stato copiato
                }
            }
            else $this->numError = Pe::DATANOTINSERTED;
        }
        else $this->NumError = Pe::QUERYERROR; //Query errata
        return $ok;
    }

    /*Controlla il formato dei dati prima di inserirli nel database */
    private function valida($ingresso){
        $ok = true;
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
        if(isset($ingresso['data']) && preg_match(Prodotto::$regex['data'],$ingresso['data'])){
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