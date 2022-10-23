<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Exceptions\InvalidValueException;

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

    private function selPriceFields(array $data): string{
        $query = "";
        if(isset($data['cPrezzo'])){
            if(isset($data['minP']) && $data['minP'] != ''){
                if(is_numeric($data['minP'])){
                    $minP = $data['minP'];
                    $query .= "AND `prezzo` >= '$minP' ";
                }
                else throw new InvalidValueException("Il prezzo minimo inserito non è valido");
            }//if(isset($data['minP']) && $data['minP'] != ''){
            if(isset($data['maxP']) && $data['maxP'] != ''){
                if(is_numeric($data['maxP'])){
                    $maxP = $data['maxP'];
                    $query .= "AND `prezzo` <= '$maxP' ";
                }
                else throw new InvalidValueException("Il prezzo massimo inserito non è valido");
            }//if(isset($data['maxP']) && $data['maxP'] != ''){
        }//if(isset($data['cPrezzo'])){
        return $query;
    }
}
?>