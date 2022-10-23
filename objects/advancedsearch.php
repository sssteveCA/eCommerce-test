<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Traits\SearchQueryBuilder;
use EcommerceTest\Interfaces\MySqlVals as Mv;

class AdvancedSearch{

    private string $sqlQuery;
    private string $table;
    private Utente $user;

    use SearchQueryBuilder;

    public function __construct(array $data)
    {
        
    }

    public function getSqlQuery(){return $this->sqlQuery;}
    public function getTable(){return $this->table;}

    private function assignValues(array $data){
        $this->table = isset($data['table']) ? $data['table'] : Mv::TABPROD;
        $this->sqlQuery = "SELECT `id` FROM `{$this->table}` ";
    }
}
?>