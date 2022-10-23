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

    private function conditionFields(array $data): string{
        $query = "";
        if(isset($data['cN']) && $data['cN'] == '1') $new = true;
        else $new = false;
        if(isset($data['cU']) && $data['cU'] == '1')$used = true;
        else $used = false;
        if(isset($data['cNs']) && $data['cNs'] == '1')$notSpecified = true;
        else $notSpecified = false;
        if($new || $used || $notSpecified){
            $query .= "AND `condizione` IN (";
            if($new) $query .= "'Nuovo' ";
            if($used){
                if($new) $query .= ", ";
                $query .= "'Usato' ";
            }
            if($notSpecified){
                if($new || $used) $query .= ", ";
                $query .= "'' ";
            }//if($notSpecified){
            $query .= ") OR `condizione` IS NULL ";
        }//if($new || $used || $notSpecified){
        return $query;
    }
}
?>