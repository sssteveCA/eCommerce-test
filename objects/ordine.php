<?php

namespace EcommerceTest\Objects;

use Dotenv\Dotenv;
use EcommerceTest\interfaces\OrderErrors as Oe;
//use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Config as Cf;
use EcommerceTest\Traits\OrdineTrait;
use EcommerceTest\Traits\SqlTrait;

define("ORDINEERR_INFONOTGETTED","1");
define("ORDINEERR_QUERYERROR","2");
define("ORDINEERR_DATANOTINSERTED","3");
define("ORDINEERR_DATANOTDELETED","4");
define("ORDINEERR_NOTADDEDCART","5");
define("ORDINEERR_IDNOTEXISTS","6");
define("ORDINEERR_NOTDELETEDCART","7");

if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
       
        return array_keys($array)[count($array)-1];
    }
}

class Ordine implements Oe/* ,Mv */{

    use SqlTrait, OrdineTrait;

    private $mysqlTable;
    private $mysqlTableAcc; //Accounts mysql table
    private $h;
    private $connesso;
    private $id;
    private $idc; //id del cliente che ha effettuato l'ordine
    private $idp; //id del prodotto che è stato ordinato
    private $idv; //id del venditore
    private $data; //data in cui l'ordine è stato effettuato
    private $quantita;
    private $totale;
    private $pagato; //0 = ordine non pagato, 1 = ordine pagato
    private $tnxId; //Id della transazione
    private $carrello; //1 se il prodotto è stato aggiunto al carrello
    private static $nOrdini = 0; //numero degli ordini effettuati dal cliente
    private $querySql;
    private $queries;
    private $numError;
    private $strError;
    private $mysqlError;
    private static $idList = array();
    public function __construct($ingresso){
        $mysqlHost=isset($ingresso['mysqlHost'])? $ingresso['mysqlHost']:$_ENV['MYSQL_HOSTNAME'];
        $mysqlUser=isset($ingresso['mysqlUser'])? $ingresso['mysqlUser']:$_ENV['MYSQL_USERNAME'];
        $mysqlPass=isset($ingresso['mysqlPass'])? $ingresso['mysqlPass']:$_ENV['MYSQL_PASSWORD'];
        $mysqlDb=isset($ingresso['mysqlDb'])? $ingresso['mysqlDb']:$_ENV['MYSQL_DATABASE'];   
        $this->mysqlTable=isset($ingresso['mysqlTable'])? $ingresso['mysqlTable']:$_ENV['TABORD'];
        $this->mysqlTableAcc=isset($ingresso['mysqlTableAcc'])? $ingresso['mysqlTableAcc']:$_ENV['TABACC'];
        $this->h = new \mysqli($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb);
        if($this->h->connect_errno !== 0){
            throw new \Exception("Connessione a MySql fallita: ".$this->h->connect_error);
        }
        $this->h->set_charset("utf8mb4");
        $this->connesso = true;
        $this->createDb($mysqlDb);
        if($this->createTab() === false){
            throw new \Exception(Oe::EXC_TABLECREATION);
        }
        $this->querySql = '';
        $this->queries = array();
        $this->numError = 0;
        $this->strError = null;
        $this->id=isset($ingresso['id'])? $ingresso['id']:null;
        //se l'ordine esiste già ottengo i dati
        if(isset($this->id)){
            $ok = $this->getOrdine();
        }
        else{
            if($this->valida($ingresso)){
                $this->idc = isset($ingresso['idc'])? $ingresso['idc']:'';
                $this->idp = isset($ingresso['idp'])? $ingresso['idp']:'';
                $this->idv = isset($ingresso['idv'])? $ingresso['idv']:'';
                $this->quantita = isset($ingresso['quantita'])? $ingresso['quantita']:'';
                $this->totale = isset($ingresso['totale'])? $ingresso['totale']:'';
                $this->data = date('Y-m-d H:i:s');
                $this->tnxId = null;
                $this->pagato = '0';
                $this->carrello = '0'; //non aggiunto al carrello
                $this->insertOrdine();
            }
            else throw new \Exception(Oe::EXC_INVALIDDATA);
        }

    }//public function __construct($ingresso){

    public function __destruct(){
        if($this->connesso){
            $this->h->close();
        }
    }
    public function __get($prop){

    }
    public function __set($prop,$val){
        
    }
    
    public function getId(){return $this->id;} //id dell'ordine ottenuto dalla lettura della tabella 'ordini'
    public function getIdp(){return $this->idp;}
    public function getIdc(){return $this->idc;}
    public function getIdv(){return $this->idv;}
    //data nel formato giorno-mese-anno ore:minuti:secondi
    public function getData(){return $this->rDate();}
    public function getQuantita(){return $this->quantita;}
    public function getTotale(){return $this->totale;}
    public function getTnxId(){return $this->tnxId;}
    //se l'ordine è stato pagato o non ancora
    public function isPagato(){
        if($this->pagato == '1')return true;
        else return false;
    }
    //se l'ordine è stato aggiunto al carrello
    public function isCarrello(){
        if($this->carrello == '1')return true;
        else return false;
    }
    public function getQuery(){return $this->querySql;}
    public function getQueries(){return $this->queries;}
    public function getNumError(){return $this->numError;}
    public function getStrError(){
        switch($this->numError){
            case Oe::INFONOTGETTED:
                $this->strError = Oe::MSG_INFONOTGETTED;
                break;
            case Oe::QUERYERROR:
                $this->strError = Oe::MSG_QUERYERROR;
                break;
            case Oe::DATANOTINSERTED:
                $this->strError = Oe::MSG_DATANOTINSERTED;
                break;
            case Oe::DATANOTDELETED:
                $this->strError = Oe::MSG_DATANOTDELETED;
                break;
            case Oe::NOTADDEDCART:
                $this->strError = Oe::MSG_NOTADDEDCART;
                break;
            case Oe::IDNOTEXISTS:
                $this->strError = Oe::MSG_IDNOTEXISTS;
                break;
            case Oe::NOTDELETEDCART:
                $this->strError = Oe::MSG_NOTDELETEDCART;
                break;
            default:
                $this->strError = null;
                break;
        }
        return $this->strError;
    }
    public function getMysqlError(){return $this->mysqlError;}

    
    
    /**
     * Put this order inside the cart 
     */
    public function addToCart($user){
        $ok = false;
        if(isset($this->id)){
            $this->querySql = <<<SQL
UPDATE `{$this->mysqlTable}` SET `carrello` = '1' WHERE `id` = '{$this->id}' AND `carrello` = '0' AND `idc` = (SELECT `id` FROM `{$this->mysqlTableAcc}` WHERE `username` = '$user'); 
SQL;
            $this->queries[] = $this->querySql;
            if($this->h->query($this->querySql) !== FALSE){
                if($this->h->affected_rows > 0){
                    $this->carrello = '1';
                    $ok = true;
                }
                else $this->numError = Oe::NOTADDEDCART; //nessun ordine aggiunto al carrello
            }
            else $this->numError = Oe::QUERYERROR; //Query errata    
        }
        else $this->numError = Oe::IDNOTEXISTS; //Id nell'oggetto Ordine non presente
        return $ok;
    }

    /**
     * Delete an order of the specific user
     */
    public function cancOrdine($user){
        $ok = false;
        $this->numError = 0;
        if(isset($this->id)){
            $this->querySql = <<<SQL
DELETE FROM `{$this->mysqlTable}` WHERE `id` = '{$this->id}' AND `idc` = (SELECT `id` FROM `accounts` WHERE `username` = '$user');
SQL;
            $this->queries[] = $this->querySql;
            if($this->h->query($this->querySql) !== FALSE){
                if($this->h->affected_rows > 0){
                    $ok = true;
                }
                else $this->numError = Oe::DATANOTDELETED; //Nessun ordine cancellato
            } 
            else $this->numError = Oe::QUERYERROR; //Query errata               
        }//if(isset($this->id)){
        else $this->numError = Oe::IDNOTEXISTS; //Id nell'oggetto Ordine non presente
        return $ok;
    }

    /**
     * Remove an order from cart
     */
    public function delFromCart($user){
        $ok = false;
        $this->numError = 0;
        if(isset($this->id)){
            $this->querySql = <<<SQL
UPDATE `{$this->mysqlTable}` SET `carrello` = '0' WHERE `id` = '{$this->id}' AND `carrello` = '1' AND `idc` = (SELECT `id` FROM `accounts` WHERE `username` = '$user');  
SQL;
            $this->queries[] = $this->querySql;
            if($this->h->query($this->querySql) !== FALSE){
                if($this->h->affected_rows > 0){
                    $this->carrello = '0';
                    $ok = true;
                }
                else $this->numError = Oe::NOTDELETEDCART; //nessun ordine cancellato dal carrello
            }
            else $this->numError = Oe::QUERYERROR; //Query errata  
        }//if(isset($this->id)){
        else $this->numError = Oe::IDNOTEXISTS; //Id nell'oggetto Ordine non presente
        return $ok;
    }
    
    //numero degli ordini effettuati dal cliente
    public static function nOrdini(){
        Ordine::$nOrdini = count(Ordine::$idList);
        return Ordine::$nOrdini;
    }
    //converto la data nel formato mese-giorno-anno ore:minuti:secondi
    private function rDate(){
        $split = preg_split('/[-:\s]+/',$this->data);
        $conv = "{$split[2]}-{$split[1]}-{$split[0]} {$split[3]}:{$split[4]}:{$split[5]}";
        return $conv;
    }

   /**
    * Get the IDs list of all orders made by the customer
    */
    public static function getIdList($host,$username,$password,$database,$tabOrdini,$tabClienti,$user){
        Ordine::$idList = array();
        $handle = new \mysqli($host,$username,$password,$database);
        if($handle->connect_errno === 0){
            $handle->set_charset("utf8mb4");
            //$queryE = $handle->real_escape_string($query);
            /*seleziono gli id degli ordini del cliente con username $user,
            viene cercata la corrispodenza tra l'id dell'account nella tabella $tabOrdini e l'id del cliente
            nella tabella $tabOrdini*/
            $query = <<<SQL
SELECT o.id 
FROM `$tabOrdini` AS `o`
INNER JOIN `$tabClienti` AS `a`
ON a.id = o.idc
WHERE a.id = (SELECT id FROM accounts WHERE username = '$user');
SQL;
            $r = $handle->query($query);
            if($r){
                if($r->num_rows > 0){
                    while($indice =  $r->fetch_array(MYSQLI_ASSOC)){
                        Ordine::$idList[] = $indice['id']; 
                    }
                }
                $r->free();
            }
            $handle->close();
        }
        else Ordine::$idList = null; //connessione a mysql fallita
        return Ordine::$idList;
    }

    /**
     * Get a specific order data
     */
    private function getOrdine(){
        $this->numError = 0;
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
                    $ordine = $r->fetch_array(MYSQLI_ASSOC);
                    $this->idc = $ordine['idc'];
                    $this->idp = $ordine['idp'];
                    $this->idv = $ordine['idv'];
                    $this->data = $ordine['data'];
                    $this->quantita = $ordine['quantita'];
                    $this->totale = $ordine['totale'];
                    $this->pagato = $ordine['pagato'];
                    $this->carrello = $ordine['carrello'];
                    //var_dump($this->carrello);
                }
                else $this->numError = Oe::INFONOTGETTED; //Impossibile ottenere le informazioni sull'ordine dal database MySql
                $r->free();
            }//if($r){
            else $this->numError = Oe::QUERYERROR; //Query errata
        }//if(isset($this->id)){
        else $this->numError = Oe::IDNOTEXISTS; //Id nell'oggetto Ordine non presente
        return $ok;
    }

    /**
     * Insert an order in MySQL table
     */
    private function insertOrdine(){
        $this->numError = 0;
        $ok = false;
        if(isset($this->tnxId)) $tnx="'{$this->tnxId}'";
        else $tnx='NULL';
        $this->querySql = <<<SQL
INSERT INTO `{$this->mysqlTable}`(`idc`, `idp`,`idv`, `data`, `quantita`, `totale`, `tnx_id`, `pagato`,`carrello`) 
VALUES (
'{$this->idc}','{$this->idp}','{$this->idv}','{$this->data}','{$this->quantita}','{$this->totale}',{$tnx},'{$this->pagato}','{$this->carrello}');
SQL;
        $this->queries[] = $this->querySql;
        if($this->h->query($this->querySql) !== FALSE){
            if($this->h->affected_rows > 0){
                        $this->id = $this->h->insert_id;
                        $ok = true;
            }
            else $this->numError = Oe::DATANOTINSERTED; //Errore durante l'inserimento dei dati nella tabella MySql
        }
        else{
            $this->numError = Oe::QUERYERROR; //Query errata
            $this->mysqlError = $this->h->error;
        } 
        return $ok;
    }

    /**
     * Update a specific order with the provided values
     * @param array $valori
     */
    public function update($valori){
        $this->numError = 0;
        $ok = false;
        $query = "UPDATE `{$this->mysqlTable}` SET ";
        foreach($valori as $k => $v){
            $query .= "`{$k}` = '{$v}'";
            if(array_key_last($valori) != $k){
                $query .= ",";
            }
        } 
        $query .= " WHERE `id` = '{$this->id}';"; 
        $this->querySql = $query;
        $this->queries[] = $this->querySql;
        if($this->h->query($this->querySql) !== FALSE){
            $ok = true;
        }
        else{
            $this->numError = Oe::QUERYERROR; //Query errata
            $this->mysqlError = $this->h->error;
        }
        return $ok;
    }
}
?>