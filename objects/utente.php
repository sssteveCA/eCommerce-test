<?php

namespace EcommerceTest\Objects;

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\UserErrors as Ue;
//use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Config as Cf;
use EcommerceTest\Traits\SqlTrait;
use EcommerceTest\Traits\UtenteConstructorTrait;
use EcommerceTest\Traits\UtenteTrait;

define("UTENTEERR_INCORRECTLOGINDATA", "1");
define("UTENTEERR_ACTIVEYOURACCOUNT", "2");
define("UTENTEERR_CONNECTFAIL", "3");
define("UTENTEERR_DATANOTUPDATED", "4");
define("UTENTEERR_DATANOTINSERTED", "5");
define("UTENTEERR_QUERYERROR", "6");
define("UTENTEERR_USERNAMEMAILEXIST", "7");
define("UTENTEERR_ACCOUNTNOTACTIVATED", "8");
define("UTENTEERR_MAILNOTSENT", "9");
define("UTENTEERR_INVALIDFIELD", "10");
define("UTENTEERR_DATANOTSET", "11");
define("UTENTEERR_ACCOUNTNOTRECOVERED", "12");
define("UTENTEERR_INVALIDDATAFORMAT", "13");

if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
       
        return array_keys($array)[count($array)-1];
    }
}

class Utente implements Ue/* ,Mv */{

    use SqlTrait,UtenteTrait,UtenteConstructorTrait;

    private $h; //handle connessione MySQL
    private $mysqlHost;
    private $mysqlUser;
    private $mysqlPass;
    private $mysqlDb;
    private $mysqlTable;
    private $connesso; //se è stata aperta una connessione MySQL
    private $id;
    private $nome;
    private $cognome;
    private $nascita;
    private $sesso;
    private $indirizzo;
    private $numero;
    private $citta;
    private $cap;
    private $email;
    private $paypalMail; //email account paypal
    private $clientId; //ID univoco del venditore paypal
    private $username;
    private $password;
    private $codAut;
    private $cambioPwd;
    private $dataCambioPwd;
    private $registrato;
    private $login; //true se il login verrà effettuato
    private $numError; //codice di errore(0 se non ci sono errori)
    private $querySql; //ultima query inviata
    private $queries; //query eseguite
    private $strError; //messaggio di errore
    public static $campi = array('id','email','clientId','username','codAut','cambioPwd');
    public static $regex = array(
        'id' => '/^[0-9]+$/',
        'email' => '/^[a-zA-Z-_0-9]{4,20}@([a-z]{3,15}\.){1,6}[a-z]{2,10}$/',
        'paypalMail' => '/^[a-zA-Z-_0-9]{4,20}@([a-z]{3,15}\.){1,6}[a-z]{2,10}$/',
        'clientId' => '/^[a-z0-9[:punct:]]{80}$/i',
        'username' => '/^.+$/i',
        'codAut' => '/^[a-z0-9]{64}$/i',
        'cambioPwd' => '/^[a-z0-9]{64}$/i'
    );
    public function __construct($ingresso){
        
        $this->setProperties($ingresso);

        if($this->registrato){
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
        }// if($this->registrato){
        //utente non registrato
        else{
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
        }//else di if($this->registrato){

    }
    public function __destruct(){
        if($this->connesso)$this->h->close();
    }
    public function __toString(){
        return $this->nome." ".$this->cognome;
    }
    public function __get($prop){

    }
    public function __set($prop,$val){
        
    }

    public function __serialize(){
        
        return [
            'mysqlHost' => $this->mysqlHost,
            'mysqlUser' => $this->mysqlUser,
            'mysqlPass' => $this->mysqlPass,
            'mysqlDb' => $this->mysqlDb,
            'mysqlTable' => $this->mysqlTable,
            'id' => $this->id,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            'nascita' => $this->nascita,
            'sesso' => $this->sesso,
            'indirizzo' => $this->indirizzo,
            'numero' => $this->numero,
            'citta' => $this->citta,
            'cap' => $this->cap,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'paypalMail' => $this->paypalMail,
            'clientId' => $this->clientId,
            'codAut' => $this->codAut,
            'cambioPwd' => $this->cambioPwd,
            'dataCambioPwd' => $this->dataCambioPwd
        ];
    }

    public function __unserialize($data){
        $this->mysqlHost = $data['mysqlHost'];
        $this->mysqlUser = $data['mysqlUser'];
        $this->mysqlPass = $data['mysqlPass'];
        $this->mysqlDb = $data['mysqlDb'];
        $this->mysqlTable = $data['mysqlTable'];
        $this->id = $data['id'];
        $this->nome = $data['nome'];
        $this->cognome = $data['cognome'];
        $this->nascita = $data['nascita'];
        $this->sesso = $data['sesso'];
        $this->indirizzo = $data['indirizzo'];
        $this->numero = $data['numero'];
        $this->citta = $data['citta'];
        $this->cap = $data['cap'];
        $this->email = $data['email'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->paypalMail = $data['paypalMail'];
        $this->clientId = $data['clientId'];
        $this->codAut = $data['codAut'];
        $this->cambioPwd = $data['cambioPwd'];
        $this->dataCambioPwd = $data['dataCambioPwd']; 
    }

    public function __wakeup()
    {
    }
    
    //ottengo l'id dell'utente tramite la lettura dalla tabella 'accounts'
    public function getId(){return $this->id;}
    public function getNome(){return $this->nome;}
    public function getCognome(){return $this->cognome;}
    //data nel formato giorno-mese-anno
    public function getNascita(){return $this->ddmmyyyy($this->nascita);}
    public function getSesso(){return $this->sesso;}
    public function getIndirizzo(){return $this->indirizzo;}
    public function getNumero(){return $this->numero;}
    public function getCitta(){return $this->citta;}
    public function getCap(){return $this->cap;}
    public function getEmail(){return $this->email;}
    public function getPaypalMail(){return $this->paypalMail;}
    public function getClientId(){return $this->clientId;}
    public function getUsername(){return $this->username;}
    public function getPassword(){return $this->password;}
    public function getCodAut(){return $this->codAut;}
    public function getCambioPwd(){return $this->cambioPwd;}
    public function getDataCambioPwd(){return $this->dataCambioPwd;}
    //ottengo l'ultima query inviata
    public function getQuery(){return $this->querySql;}
    public function getQueries(){return $this->queries;}
    public function getNumError(){return $this->numError;}
    //messsaggio di errore
    public function getStrError(){
        switch($this->numError){
            case 0:
                $this->strError = null;
                break;
            case Ue::INCORRECTLOGINDATA:
                $this->strError = Ue::MSG_INCORRECTLOGINDATA;
                break;
            case Ue::ACTIVEYOURACCOUNT:
                $this->strError = Ue::MSG_ACTIVEYOURACCOUNT;
                break;
            case Ue::CONNECTFAIL:
                $this->strError = Ue::MSG_CONNECTFAIL;
                break;
            case Ue::DATANOTUPDATED:
                $this->strError = Ue::MSG_DATANOTUPDATED;
                break;
            case Ue::DATANOTINSERTED:
                $this->strError = Ue::MSG_DATANOTINSERTED;
                break;
            case Ue::QUERYERROR:
                $this->strError = Ue::MSG_QUERYERROR;
                break;
            case Ue::USERNAMEMAILEXIST:
                $this->strError = Ue::MSG_USERNAMEMAILEXIST;
                break;
            case Ue::ACCOUNTNOTACTIVATED:
                $this->strError = Ue::MSG_ACCOUNTNOTACTIVATED;
                break;
            case Ue::MAILNOTSENT:
                $this->strError = Ue::MSG_MAILNOTSENT;
                break;
            case Ue::INVALIDFIELD:
                $this->strError = Ue::MSG_INVALIDFIELD;
                break;
            case Ue::DATANOTSET:
                $this->strError = Ue::MSG_DATANOTSET;
                break;
            case Ue::ACCOUNTNOTRECOVERED:
                $this->strError = Ue::MSG_ACCOUNTNOTRECOVERED;
                break;
            case Ue::INVALIDDATAFORMAT:
                $this->strError = Ue::MSG_INVALIDDATAFORMAT;
                break;
            default:
                $this->strError = null;
                break;
        }
        return $this->strError;
    }
    public function isLogin(){return $this->login;} //stato del login (true se è stato effettuato)
    public function isRegistrato(){return $this->registrato;} //true se l'utente ha già il suo account

    public function setNome($nome){$this->nome = $nome;}
    public function setCognome($cognome){$this->cognome = $cognome;}
    public function setNascita($nascita){$this->nascita = $nascita;}
    public function setSesso($sesso){$this->sesso = $sesso;}
    public function setIndirizzo($indirizzo){$this->indirizzo = $indirizzo;}
    public function setNumero($numero){$this->numero = $numero;}
    public function setCitta($citta){$this->citta = $citta;}
    public function setCap($cap){$this->cap = $cap;}
    public function setEmail($email){$this->email = $email;}
    public function setPaypalMail($paypalMail){$this->paypalMail = $paypalMail;}
    public function setClientId($clientId){$this->clientId = $clientId;}
    public function setUsername($username){$this->username = $username;}
    private function setPassword($password){$this->password = $password;}
    private function setCodAut($codAut){$this->codAut = $codAut;}
    private function setCambioPwd($cambioPwd){$this->cambioPwd = $cambioPwd;}
    private function setDataCambioPwd($dataCambioPwd){$this->dataCambioPwd = $dataCambioPwd;}


    /**
     * Get the rows from the user table that match with the provided WHERE clauses
     * @param array $where
     */
    private function getUserData($where){
        $this->numError = 0;
        $utente = false;
        $this->h->set_charset("utf8mb4");
        $query = <<<SQL
SELECT * FROM `{$this->mysqlTable}` WHERE 
SQL;
        foreach($where as $k => $v){
            $valE = $this->h->real_escape_string($v);
            $query .= " `{$k}` = '{$valE}'";
            //se non è l'ultima chiave dell'array aggiungo l'operatore AND
            if($k !== array_key_last($where))$query .= " AND";
        }
        $query .= ";";
        $this->querySql = $query;
        $this->queries[] = $this->querySql;
        $r = $this->h->query($this->querySql);
        if($r){
            if($r->num_rows == 1){
                //L'utente è stato trovato nel DB
                $utente = $r->fetch_array(MYSQLI_ASSOC);
            }
            $r->free();
        }
        else{
            $this->numError = Ue::QUERYERROR; //query errata
        }
        return $utente;
    }

    /**
     * Insert the data in the table
     */
    private function insertData($ingresso){
        $this->numError = 0;
        $ok = false;
        $campi = '';
        $valori = '';
        foreach($ingresso as $k => $v){
            $campi .= "`{$k}`";
            $valE = $this->h->real_escape_string($v);
            $valori .= "'{$valE}'";
            if($k !== array_key_last($ingresso)){
                $campi .= ",";
                $valori .= ",";
            }
        }
        $this->querySql = <<<SQL
INSERT INTO `{$this->mysqlTable}` ({$campi}) VALUES ({$valori});
SQL;
        $this->queries[] = $this->querySql;
        if($this->h->query($this->querySql) === TRUE){
            if($this->h->affected_rows > 0){
                $ok = true;
            }
            else $this->numError = Ue::DATANOTINSERTED; //dati registrazione non inseriti nel database
        }//if($h->query($this->querySql) === TRUE){
        else $this->numError = Ue::QUERYERROR; //query errata
        return $ok;
    }

    /**
     * The user send an email
     */
    public function sendMail($to,$subject,$body,$headers,$from = ''){
        $this->numError = 0;
        $emData = [
            'to' => $to, 'subject' => $subject, 'body' => $body
        ];
        if($from != '') $emData['from'] = $from;
        $em = new EmailManager($emData);
        if($em->getErrno() != 0){
            $this->numError = Ue::MAILNOTSENT;
            return false;
        }
        return true;
        /* $send = mail($to,$subject,$body,$headers);
        if(!$send) $this->numError = Ue::MAILNOTSENT; //email non inviata
        return $send; */
    }

    /*modifico uno o più campi della tabella $this->mysqlTable,
    $valori = array che contiene i campi con i rispettivi valori aggiornati,
    $where = array che contiene i campi e i valori nella clausola WHERE
    $operatore = operatore logico da utilizzare nella WHERE*/
    /**
     * Update the user table with the provided values and filter
     * @param array $valori the values to insert in the UPDATE statement
     * @param array $where the filter to select the rows to update
     * @param string $operatore the logical operator to join multiple WHERE clauses
     */
    public function update($valori,$where,$operatore = 'AND'){
        $this->numError = 0;
        $ok = false;
        if(is_array($valori) && is_array($where)){
            $query = <<<SQL
UPDATE `{$this->mysqlTable}` SET 
SQL;
            foreach($valori as $k => $v){
                if($k == 'password'){
                    $v = password_hash($v,PASSWORD_DEFAULT);
                }
                $valE = $this->h->real_escape_string($v);
                $query .= " `{$k}` = '{$valE}'";
                if($k !== array_key_last($valori)){
                    $query .= ", ";
                }
            }
            $query .= " WHERE";
            foreach($where as $k => $v){
                $valE = $this->h->real_escape_string($v);
                $query .= " `{$k}` = '{$valE}'";
                if($k !== array_key_last($where)){
                    $query .= " {$operatore} ";
                }
            }
            $query .= ";";
            $this->querySql = $query;
            if($this->h->query($query) === TRUE){
                $ok = true;
                $this->setValues($valori);
                
            }
            else $this->numError = Ue::DATANOTUPDATED; //dati non aggiornati
        }//if(is_array($valori) && is_array($where)){ 
        return $ok;
    }

    
}
?>