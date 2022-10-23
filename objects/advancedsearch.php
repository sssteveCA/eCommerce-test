<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Exceptions\IncorrectUserInstanceFormatException;
use EcommerceTest\Exceptions\NoUserInstanceException;
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
        if(!$data['user']) throw new NoUserInstanceException;
        if(!$data['user'] instanceof Utente) throw new IncorrectUserInstanceFormatException;
        $this->table = isset($data['table']) ? $data['table'] : Mv::TABPROD;
        $this->setQuery($data);
    }

    private function setQuery(array $data){
        $this->sqlQuery = "SELECT `id` FROM `{$this->table}` ";
        $this->sqlQuery .= $this->searchField($data);
        $this->sqlQuery .= $this->selCatField($data);
        $this->sqlQuery .= $this->selPriceFields($data);
        $this->sqlQuery .= "ORDER BY `data` DESC LIMIT 30";
    }


}
?>