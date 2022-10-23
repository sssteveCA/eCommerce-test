<?php

namespace EcommerceTest\Traits;

/**
 * This trait contains method to build SQL query needed by advanced search
 */
trait SearchQueryBuilder{


    private function searchField(array $data): string{
        if(isset($data['ricerca']) && $data['ricerca'] != ''){
            $search = $data['ricerca'];
            return "AND `nome` REGEXP '$search' ";
        }
        return "";
    }

    private function selCatField(array $data): string{
        if(isset($data['selCat']) && $data['selCat'] != '' && $data['selCat'] != 'Tutte le categorie'){
            $type = $data['selCat'];
            return "AND `tipo` = '$type' ";
        }
        return "";
    }
}
?>