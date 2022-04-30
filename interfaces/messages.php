<?php

namespace EcommerceTest\Interfaces;

//string messages constants
interface Messages{

    //success messages
    const ORDERADDED = 'Ordine aggiunto al carrello';
    const ORDERDELETED = 'Ordine eliminato dal carrello';
   
    //error messages
    const ERR_ALREALDYCART = 'Il prodotto selezionato è già presente nel carrello';
    const ERR_INVALIDOPERATION = 'Operazione specificata non valida';
    const ERR_ORDERDELETEINVALIDID = 'Impossibile eliminare l\' ordine dal carrello perché l\'id non è valido';
    const ERR_ORDERINVALIDDATA = 'Impossibile aggiungere l\' ordine al carrello perché uno o più dati passati non sono validi';
}
?>