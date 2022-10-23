<?php

namespace EcommerceTest\Traits;

use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Interfaces\Messages as M;

/**
 * Methods to generate the table for advanced search results
 */
trait SearchTable{

    private function table(){
        $table = "";
        $idProductsList = Prodotto::getIdList(Mv::HOSTNAME,Mv::USERNAME,Mv::PASSWORD,Mv::DATABASE,$this->getSqlQuery());
        if($idProductsList != null){
            if(!empty($idProductsList)){
                $table =<<< HTML
<table class="table table-striped">
    <tbody>
HTML;
                $table .= <<<HTML
    </tbody>
</table>
HTML;
            }//if(!empty($idProductsList)){
            else{
                $message = M::ADVANCEDSEARCHEMPTY;
                $table .=<<<HTML
<div id="null" class="alert alert-danger" role="alert">{$message}</div>
HTML;
            } 
        }//if($idProductsList != null){
        else{
            $message = M::ERR_ADVANCEDSEARCH;
            $table .= <<<HTML
<div id="null" class="alert alert-danger" role="alert">{$message}</div>
HTML;

        }
        $this->htmlTable = $table;
    }

    /**
     * Single product table row
     * @param int $idP product id
     * @return string product table row content
     */
    private function tableRow(int $idP){

    }

}

?>