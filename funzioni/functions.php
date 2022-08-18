<?php

use EcommerceTest\Config as Cf;
use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Interfaces\Messages as Msg;

require_once('config.php');
//dato l'id dell'ordine, lo cancella
function cancOrdine($id,$user){
    $ordine = array();
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        $ordine['msg'] = 'Connessione a MySQL fallita: '.$h->connect_error;
    }
    else{
        $h->set_charset("utf8mb4");
        $query = <<<SQL
        DELETE FROM `ordini` WHERE `id` = '$id' AND `idc` = (SELECT `id` FROM `accounts` WHERE `username` = '$user');
SQL;
        if($h->query($query) !== FALSE){
            if($h->affected_rows > 0){
                $ordine['msg'] = 'Ordine cancellato con successo';
                $ordine['del'] = '1'; //se l'operazione è andata a buon fine
            }
            else{
                $ordine['msg'] = 'ERRORE: nessun ordine cancellato';
            }
        }
        else $ordine['msg'] = 'Query errata: '.$query;
        $h->close();
    }
    return $ordine;
}

//genera una stringa casuale
function casuale($ls, $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYzabcdefghijklmnopqrstuvwxyz0123456789')
{
    $lc = strlen($c) - 1;
    $s = '';
    for($i = 0; $i < $ls; $i++)
    {
        $j = mt_rand(0,$lc);
        $s .= $c[$j];
    }
    return $s;
}

//inserisce i dati dell'ordine nella tabella MySql 'ordini', true se li ha inseriti
function creaOrdine($idc,$idp,$np,$tot){
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //exit('Connessione a MySQL fallita: '.$h->connect_error);
        return false;
    }
    $h->set_charset("utf8mb4");
    $data_ordine = date('Y-m-d H:i:s');
    $query = <<<SQL
    INSERT INTO ordini(`idc`,`idp`,`data`,`quantita`,`totale`) 
    VALUES('$idc','$idp','$data_ordine','$np','$tot');
SQL;
    if($h->query($query) !== FALSE){
        $h->close();
        return true;
    }
    else{
        $h->close();
        return false;
    }
}

//converti la data(MySql datetime) nel formato giorno/mese/anno ore:minuti:secondi
function datetimeConv($data){
    $split = preg_split('/[-:\s]+/',$data);
    $conv = "{$split[2]}-{$split[1]}-{$split[0]} {$split[3]}:{$split[4]}:{$split[5]}";
    return $conv;
}

//converti la data nel formato giorno/mese/anno
function ddmmyyyy($data){
    $dataArray = explode('-',$data);
    $dataArray2 = array($dataArray[2],$dataArray[1],$dataArray[0]);
    $data2 = implode('-',$dataArray2);
    return $data2;
}

/*1 = ci sono campi con lo stesso nome e con lo stesso valore
  0 = non ci sono campi con lo stesso valore già esstenti
  -1 = la query non è stata eseguita */
  function Exists($campo,$campoW,$str){
    $mysqli = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    if($mysqli !== FALSE){
        $mysqli->set_charset("utf8mb4");
        $query = "SELECT `".$campo."` from `accounts` WHERE `".$campoW."` = '$str'";
        //echo "$query<br>";
        $r = $mysqli->query($query);
        if($r){
            if($r->num_rows > 0){
                $mysqli->close();
                $r->free();
                return 1;
            }
            else{
                $mysqli->close();
                $r->free();
                return 0;
            }
        }
        $mysqli->close();
        
    }
    else{
        return -1;
    }
}

/*1 = ci sono campi con lo stesso nome e con lo stesso valore
  0 = non ci sono campi con lo stesso valore già esstenti
  -1 = la query non è stata eseguita */
  function Exists2($where){
    $mysqli = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    if($mysqli !== FALSE){
        $mysqli->set_charset("utf8mb4");
        $query = "SELECT * from `accounts` WHERE {$where};";
        //echo "$query<br>";
        $r = $mysqli->query($query);
        if($r){
            if($r->num_rows > 0){
                $ret = 1;
            }
            else{
                $ret = 0;
            }
            $r->free();
        }
        $mysqli->close();
    }
    else{
        $ret = -1;
    }
    return $ret;
}

//ottieni l'ID più grande di una tabella
function getMaxId($table){
    $id = -1;
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno == 0){
        $h->set_charset("utf8mb4");
        $query = <<<SQL
SELECT MAX(`id`) AS `id` FROM `{$table}`;
SQL;
        $r = $h->query($query);
        if($r){
            if($r->num_rows > 0){
                $max = $r->fetch_array(MYSQLI_ASSOC);
                $id = $max["id"];
            }
            else $id = -3;
            $r->free();
        }
        else $id = -2;
        $h->close();
    }
    return $id;
}

//mostra tutti gli ordini effettuati da un utente
function getOrdini($user){
    $ordini = array();
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //echo 'Connessione a MySQL fallita: '.$h->connect_error;
        $ordini['msg'] = 'Connessione a MySql fallita';
    }
    else{
        $h->set_charset("utf8mb4");
        $query = <<<SQL
        SELECT a.id, o.id, o.idc, o.idp, o.data, o.quantita, o.totale, o.pagato 
        FROM `ordini` AS `o`
        INNER JOIN `accounts` AS `a`
        ON a.id = o.idc
        WHERE a.id = (SELECT id FROM accounts WHERE username = '$user');
SQL;
        $r = $h->query($query);
        if($r){
            if($r->num_rows > 0){
                $ordini['tab'] = '1'; //se verrà creata la tabella con gli ordini
                $i = 0;
                while($row = $r->fetch_array(MYSQLI_NUM)){
                    $ordini[$i]['id'] = $row[1];
                    $ordini[$i]['idc'] = $row[2];
                    $ordini[$i]['idp'] = $row[3];
                    $ordini[$i]['data'] = datetimeConv($row[4]);
                    $ordini[$i]['quantita'] = $row[5];
                    $ordini[$i]['totale'] = $row[6];
                    $ordini[$i]['pagato'] = $row[7];
                    $i++;
                }

            }
            else $ordini['msg'] = 'Nessun ordine effettuato';
            $r->free();
        }
        $h->close();
    }    
    return $ordini;
}

//restituisce la password fornendo l'indirizzo mail
function getPassword($email){
    $result = array();
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //echo 'Connessione a MySQL fallita: '.$h->connect_error;
        $result['errore'] = 'Connessione a MySQL fallita: '.$h->connect_error;
    } 
    else{
        $h->set_charset("utf8mb4");
        $query = <<<SQL
        SELECT `password` FROM `accounts` WHERE `email` = '$email';
SQL;
        $r = $h->query($query);
        if($r){
            if($r->num_rows > 0){
                $riga = $r->fetch_array(MYSQLI_ASSOC);
                $result['password'] = $riga['password'];
            }
            else{
                $result['errore'] = 'Indirizzo mail non valido';
            }
            $r->free();
        }
        else{
            $result['errore'] = 'Query errata';
        }
        $h->close();
    }
    return $result;
}

//restituisce tutte le informazioni su un prodotto specifico con id = $idp
function getProdotto($idp){
    $prodotto = array();
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    if($h->connect_errno){
        $prodotto["msg"] =  'Connessione a MySQL fallita: '.$h->connect_error;
        $prodotto["done"] = '0';
    }
    else{
        $h->set_charset("utf8mb4");
        if(is_numeric($idp)){
            $query = <<<SQL
            SELECT * FROM `prodotti` WHERE `id` = '$idp';
SQL;
            $r = $h->query($query);
            if($r){
                if($r->num_rows > 0){
                    $row = $r->fetch_array(MYSQLI_ASSOC);
                    foreach($row as $k => $v){
                        $prodotto[$k] = $v;
                    }
                    $prodotto["done"] = '1';
                }
                else{
                     $prodotto["msg"] = "L'oggetto specificato non esiste";
                     $prodotto["done"] = '0';
                }
                $r->free();
            }
            else{
                 $prodotto["msg"] = "Query errata";
                 $prodotto["done"] = '0';
            }
        }
        else{
            $prodotto["msg"] = "Id del prodotto non valido";
            $prodotto["done"] = '0';
        }
        $h->close();  
    }
}

//ottieni tutte le informazioni dell'utente $user
function getUserData($user){
    $info = array();
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //exit('Connessione a MySQL fallita: '.$h->connect_error);
        $info["errore"] = "Connessione a MySql fallita: ".$h->connect_error;
    }
    else{
        $h->set_charset("utf8mb4");
        $query = <<<SQL
        SELECT * FROM `accounts` WHERE `username` = '$user';
SQL;
        $r = $h->query($query);
        if($r){
            if($r->num_rows > 0){
                $row = $r->fetch_array(MYSQLI_ASSOC);
                foreach($row as $k => $v){
                    $info[$k] = $v;
                }
            }
            else $info["errore"] = "Username non valido";
            $r->free();
        }
        else{
            $info["errore"] = "Query errata";
        }
    }
    $h->close();
    return $info;
}

//inserisce i dati passati nel form se sono validi nel database MySql
function InsertData($nome,$cognome,$nascita,$sesso,$ind,$num,$citta,$cap,$email,$user,$password,&$codAut){
    //echo "$nome,$cognome,$nascita,$sesso,$email,$user,$password<br>";
    $mysqli = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    if($mysqli !== FALSE){
        $mysqli->set_charset("utf8mb4");
        //consente di trasformare caratteri speciali in caratteri inseribili in una stringa
        $nomeE=$mysqli->real_escape_string($nome);
        $cognomeE=$mysqli->real_escape_string($cognome);
        $indE = $mysqli->real_escape_string($ind);
        $numE = $mysqli->real_escape_string($num);
        $cittaE=$mysqli->real_escape_string($citta);
        $userE=$mysqli->real_escape_string($user);
        $emailE=$mysqli->real_escape_string($email);
        $passwordE=$mysqli->real_escape_string($password);
        $codAut = str_replace('.','a',microtime());
        $codAut = str_replace(' ','b',$codAut);
        $lCod = strlen($codAut);
        $lCas = 64 - $lCod;
        $codAut = casuale($lCas).$codAut;
        //inserisco i dati nel database
        $query = <<<SQL
        INSERT INTO `accounts`(`nome`,`cognome`,`nascita`,`sesso`,`indirizzo`,`numero`,`citta`,`cap`,`email`,`username`,`password`,`codAut`)
        VALUES('$nomeE','$cognomeE','$nascita','$sesso','$indE','$numE','$cittaE','$cap','$emailE','$userE','$passwordE','$codAut');
SQL;
        //echo "$query<br>";
        if($mysqli->query($query) !== FALSE){
            echo 'Registrazione completata con successo<br>, attiva l\' account accedendo alla tua casella di posta';
            $mysqli->close();
            return true;
        }
        else{
            echo 'La query non è stata eseguita<br>';
            echo $mysqli->errno.'<br>';
            echo $mysqli->error.'<br>';
            $mysqli->close();
            return false;
        } 
    }
    else{
        echo 'Connessione a Mysql fallita<br>';
        return false;
    }
}

function insertProduct($nome,$imgPath,$tipo,$prezzo,$condizione,$stato,$citta,$descrizione){
    $insert = -1;
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno == 0){
        $h->set_charset("utf8mb4");
        $nomeE = $h->real_escape_string($nome);
        $statoE = $h->real_escape_string($stato);
        $cittaE = $h->real_escape_string($citta);
        $data = date('Y-m-d',time());
        $descrizioneE = $h->real_escape_string($descrizione);

        $query = <<<SQL
INSERT INTO `prodotti`(`nome`, `tipo`, `prezzo`, `condizione`, `stato`, `citta`, `data`, `descrizione`,`immagine`) 
VALUES (
    '$nomeE','$tipo','$prezzo','$condizione','$statoE','$cittaE','$data','$descrizioneE',
    CONCAT('img/',
    (SELECT `AUTO_INCREMENT`
    FROM  INFORMATION_SCHEMA.TABLES
    WHERE TABLE_SCHEMA = 'stefano'
    AND   TABLE_NAME   = 'prodotti'),
    '.jpg')
);
SQL;

        if($h->query($query) !== FALSE){
            if($h->affected_rows > 0){
                $id = $h->insert_id;
                if($id > 0){
                    if(copy($imgPath,"../img/{$id}.jpg")){
                        $insert = 1;
                    }
                    else $insert = -5;
                }
                else $insert = -3;
            }
        }
        else $insert = -2;
        $h->close();
    }
    return $insert;
}

/*1 se l'utente è maschio
  0 se l'utente è femmina
 -1 in caso di errore*/
 function isMaschio($user){
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //exit('Connessione a MySQL fallita: '.$h->connect_error);
        return -1;
    }
    $h->set_charset("utf8mb4");
    $query = <<<SQL
    SELECT `sesso` FROM `accounts` WHERE `username` = '$user';
SQL;
    $r = $h->query($query);
    if($r){
        if($r->num_rows > 0){
            $row = $r->fetch_array(MYSQLI_ASSOC);
            if($row["sesso"] == "Maschio"){
                $h->close();
                $r->free();
                return 1;
            }
            else{
                $h->close();
                $r->free();
                return 0;
            }
        }
        else{
            $h->close();
            $r->free();
            return -1;
        }
    }
    $h->close();
    return -1;
}

//modifica la password dell'account con indirizzo mail $email
function setPassword($email){
    $result = array();
    //apro la connessione al server MySQL
    $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($h->connect_errno){
        //echo 'Connessione a MySQL fallita: '.$h->connect_error;
        $result['errore'] = 'Connessione a MySQL fallita: '.$h->connect_error;
    } 
    else{
        $h->set_charset("utf8mb4");
        $newPwd = casuale(10);
        $pwdC = password_hash($newPwd,PASSWORD_DEFAULT);
        $pwdCE = $h->real_escape_string($pwdC);
        $query = <<<SQL
        UPDATE `accounts` SET `password` = '$pwdCE' WHERE `email` = '$email';
SQL;
        $r = $h->query($query);
        if($r){
            if($h->affected_rows > 0){
                $result['password'] = $newPwd; 
            }
            else{
                $result['errore'] = 'Indirizzo mail non valido';
            }
        }
        else{
            $result['errore'] = 'Query errata';
        }
        $h->close();
    }
    return $result;
}

/* Modifica i dati dell'account con cui è stato fatto il login
$campoW = campo che il where deve controllare
$valW = valore del campo da controllare
$campo = campo da modificare
$valore = valore del campo da modificare */
function update($campoW,$valW,$campo,$valore){
    //apro la connessione al server MySQL
    $mysqli = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
    //errore
    if($mysqli->connect_errno){
        //echo 'Connessione a MySQL fallita: '.$mysqli->connect_error.'<br>';
        return false;
    }
    $mysqli->set_charset("utf8mb4");
    $valoreE = $mysqli->real_escape_string($valore);
    $query = <<<SQL
    UPDATE `accounts` SET `$campo` = '$valoreE' WHERE `$campoW` = '$valW';
SQL;
    if($mysqli->query($query) !== FALSE){
        if($mysqli->affected_rows > 0){
            //echo 'Aggiornamento dati profilo effettuato<br>';
            $mysqli->close();
            return true;
        }
        else{
            //echo 'Nessun dato del profilo aggiornato<br>';
            $mysqli->close();
            return false;
        }
    }
    else{
        //echo 'Query errata<br>';
        $mysqli->close();
        return false;
    } 
}
?>