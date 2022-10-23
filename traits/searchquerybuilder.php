<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Exceptions\InvalidValueException;

/**
 * This trait contains method to build SQL query needed by advanced search
 */
trait SearchQueryBuilder{

    private function cityField(array $data): string{
        if(isset($data['cCitta']) && $data['cCitta'] == '1'){
            if(isset($data['citta']) && $data['citta'] != ''){
                $city = $data['citta'];
                return "AND `citta` = '$city' ";
            }
        }//if(isset($data['cCitta']) && $data['cCitta'] == '1'){
        return "";
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
            $query .= ") ";
            if($notSpecified) $query .= "OR `condizione` IS NULL ";
        }//if($new || $used || $notSpecified){
        return $query;
    }

    private function oldDateField(array $data): string{
        if(isset($data['dataI'])){
            if(isset($data['oDate']) && $data['oDate'] != ''){
                $dateA = explode('-',$data['oDate']);
                if(checkdate($dateA[1],$dateA[2],$dateA[0])){
                    $date = $data['oDate'];
                    return "AND `data` >= '$date' ";
                }
                else throw new InvalidValueException("Data più vecchia non valida");
            }
        }
        return "";
    }

    private function recentDateField(array $data): string{
        if(isset($data['dataF'])){
            if(isset($data['rDate']) && $data['rDate'] != ''){
                $dateA = explode('-',$data['rDate']);
                if(checkdate($dateA[1],$dateA[2],$dateA[0])){
                    $date = $data['rDate'];
                    return "AND `data` <= '$date' ";
                }
                else throw new InvalidValueException("Data più recente non valida");
            }
        }
        return "";
    }

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

   

    private function stateField(array $data): string{
        if(isset($data['cStato']) && $data['cStato'] == '1'){
            if(isset($data['stato']) && $data['stato'] != ''){
                $state = $data['stato'];
                return "AND `stato` = '$state' ";
            }
        }//if(isset($data['cStato']) && $data['cStato'] == '1'){
        return "";
    }
}
?>