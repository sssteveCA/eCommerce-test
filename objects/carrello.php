<?php
//require_once('../funzioni/config.php');

namespace EcommerceTest\Objects;

use EcommerceTest\Interfaces\MySqlVals as Mv;

class Carrello implements Mv{
    /* const HOST = 'localhost';
    const USERNAME = 'root';
    const PASSWORD = '';
    const DATABASE = 'stefano'; 
    const TABLE = 'ordini';
    const TABLE_USERS = 'accounts'; */
    private static $cartIdos = array();
    private static $nProdotti = 0;
    //ottengo gli id degli ordini aggiunti al carrello
    public static function getCartIdos($user){
        Carrello::$cartIdos = array();
        Carrello::$nProdotti = 0;
        $h = new \mysqli(Mv::HOSTNAME,Mv::USERNAME,Mv::PASSWORD,Mv::DATABASE);
        if($h->connect_errno === 0){
            $h->set_charset("utf8mb4");
            $oTable = Mv::TABORD;
            $aTable = Mv::TABACC;
            $query = <<<SQL
SELECT `id`, `idv` FROM `{$oTable}` WHERE `idc` = (
    SELECT `id` FROM `{$aTable}` WHERE `username` = '$user'
)
AND `carrello` = '1'
ORDER BY `idv`;
SQL;

            $r = $h->query($query);
            if($r){
                if($r->num_rows > 0){
                    while($indice =  $r->fetch_array(MYSQLI_ASSOC)){
                        Carrello::$nProdotti++;
                        Carrello::$cartIdos[$indice['idv']][] = $indice['id']; 
                    }
                }
                $r->free();
            }
            $h->close();
        }
        return Carrello::$cartIdos;
    }
    //numero dei prodotti dentro il carrello (chiama prima la funzione 'getCartIdos')
    public static function nProdotti(){
        return Carrello::$nProdotti;
    }
}
?>