<?php

if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
       
        return array_keys($array)[count($array)-1];
    }
}

$table = '`tabella`';
$set = array();
$set['nome'] = 'val1';
$set['cognome'] = 'val2';
$where = array();
$where['username'] = 'Usr';
$where['email'] = 'mail';
$operatore = 'AND';
$query = <<<SQL
UPDATE 
SQL;
$query .= $table." SET ";
foreach($set as $k => $v){
    $query .= "`{$k}` = '{$v}'";
    if($k !== array_key_last($set)){
        $query .= ", ";
    }
}
$query .= " WHERE ";
foreach($where as $k => $v){
    $query .= "`{$k}` = `{$v}`";
    if($k !== array_key_last($where)){
        $query .= " AND ";
    }
}
$query .= ";";
echo $query;
?>