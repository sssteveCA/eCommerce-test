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
            else $table .= '<p id="null">'.M::ADVANCEDSEARCHEMPTY.'</p>';
        }//if($idProductsList != null){
        else $table .= '<p id="null">'.M::ERR_ADVANCEDSEARCH.'</p>';
        $this->htmlTable = $table;
    }

}

?>