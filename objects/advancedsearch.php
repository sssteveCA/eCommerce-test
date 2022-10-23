<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Exceptions\IncorrectUserInstanceFormatException;
use EcommerceTest\Exceptions\NoUserInstanceException;
use EcommerceTest\Traits\SearchQueryBuilder;
use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Traits\SearchTable;

class AdvancedSearch{

    private string $sqlQuery;
    private string $table;
    private Utente $user;

    use SearchQueryBuilder,SearchTable;

    public function __construct(array $data)
    {
        $this->assignValues($data);
        $this->setQuery($data);
    }

    public function getSqlQuery(){return $this->sqlQuery;}
    public function getTable(){return $this->table;}

    private function assignValues(array $data){
        if(!$data['user']) throw new NoUserInstanceException;
        if(!$data['user'] instanceof Utente) throw new IncorrectUserInstanceFormatException;
        $this->user = $data['user'];
        $this->table = isset($data['table']) ? $data['table'] : Mv::TABPROD;
    }

    private function setQuery(array $data){
        $this->sqlQuery = "SELECT `id` FROM `{$this->table}` ";
        $idC = $this->user->getId();
        $this->sqlQuery = "WHERE `idU` <> '{$idC}' ";
        $this->sqlQuery .= $this->searchField($data);
        $this->sqlQuery .= $this->selCatField($data);
        $this->sqlQuery .= $this->selPriceFields($data);
        $this->sqlQuery .= $this->conditionFields($data);
        $this->sqlQuery .= $this->oldDateField($data);
        $this->sqlQuery .= $this->recentDateField($data);
        $this->sqlQuery .= $this->stateField($data);
        $this->sqlQuery .= $this->cityField($data);
        $this->sqlQuery .= "ORDER BY `data` DESC LIMIT 30";
    }


}
?>