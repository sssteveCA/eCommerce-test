<?php

namespace EcommerceTest\Traits;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Prodotto;
//use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Interfaces\Messages as M;

/**
 * Methods to generate the table for advanced search results
 */
trait SearchTable{

    private function table(){
        $table = "";
        $idProductsList = Prodotto::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$this->sqlQuery);
        if($idProductsList != null){
            if(!empty($idProductsList)){
                $table =<<< HTML
<table class="table table-striped">
    <thead>
        <th scope="col">Nome</th>
        <th scope="col">Immagine</th>
        <th scope="col">Categoria</th>
        <th scope="col">Prezzo</th>
        <th scope="col">Dettagli</th>
    </thead>
    <tbody>
HTML;
                foreach($idProductsList as $idP){
                    $table .= $this->tableRow($idP);
                }//foreach($idProductsList as $idP){
                $table .= <<<HTML
    </tbody>
</table>
HTML;
            }//if(!empty($idProductsList)){
            else{
                $message = M::ADVANCEDSEARCHEMPTY;
                $table .=<<<HTML
<div id="null" class="alert alert-warning text-center my-5" role="alert">{$message}</div>
HTML;
            } 
        }//if($idProductsList != null){
        else{
            $message = M::ADVANCEDSEARCHEMPTY;
            $table .= <<<HTML
<div id="null" class="alert alert-warning text-center my-5" role="alert">{$message}</div>
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
        $product = new Prodotto(array('id' => $idP));
        $name = $product->getNome();
        $image = $product->getImmagine();
        $type = $product->getTipo();
        $price = sprintf("%.2f",$product->getPrezzo());
        $tr = <<<HTML
<tr>
    <td class="nome">{$name}</td>
    <td class="timg"><img src="{$image}"></td>
    <td class="tipo">{$type}</td>
    <td class="prezzo">{$price}€</td>
    <td class="dettagli">
        <form method="get" action="/product/{$idP}">
            <button type="submit" class="btn btn-success">DETTAGLI</button>
        </form>    
    </td>
</tr>
HTML;
        return $tr;
    }

}

?>