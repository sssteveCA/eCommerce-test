<?php

namespace EcommerceTest\Objects;

use Dotenv\Dotenv;
use EcommerceTest\Exceptions\IncorrectUserInstanceFormatException;
use EcommerceTest\Exceptions\NoUserInstanceException;
use EcommerceTest\Traits\SearchQueryBuilder;
//use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Traits\Error;
use EcommerceTest\Traits\SearchTable;

class AdvancedSearch{

    private string $htmlTable = "";
    private string $sqlQuery;
    private string $table;
    private Utente $user;

    use Error,SearchQueryBuilder,SearchTable;

    public function __construct(array $data)
    {
        $this->assignValues($data);
        $this->setQuery($data);
        $this->table();
    }
    
    public function getHtmlTable(){return $this->htmlTable;}
    public function getSqlQuery(){return $this->sqlQuery;}
    public function getTable(){return $this->table;}
    public function getError(){
        switch($this->errno){
            default:
                $this->error = null;
        }
        return $this->error;
    }

    private function assignValues(array $data){
        if(!$data['user']) throw new NoUserInstanceException;
        if(!$data['user'] instanceof Utente) throw new IncorrectUserInstanceFormatException;
        $this->user = $data['user'];
        $this->table = isset($data['table']) ? $data['table'] : $_ENV['TABPROD'];
    }

    private function setQuery(array $data){
        $this->sqlQuery = "SELECT `id` FROM `{$this->table}` ";
        $idC = $this->user->getId();
        $this->sqlQuery .= "WHERE `idU` <> '{$idC}' ";
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