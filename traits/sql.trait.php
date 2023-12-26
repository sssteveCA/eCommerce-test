<?php

namespace EcommerceTest\Traits;

trait SqlTrait{

    private function createDb(string $mysqlDb){
        $ok = true;
        $this->numError = 0;
        $this->querySql = <<<SQL
CREATE DATABASE IF NOT EXISTS `{$mysqlDb}`;
SQL;
        $this->queries[] = $this->querySql;
        $this->h->query($this->querySql);
        $this->h->select_db($mysqlDb);
        return $ok;
    }
}
?>