<?php

namespace EcommerceTest\Interfaces;

//string messages constants
interface Messages{

    //success messages
    const ORDERADDED = 'Ordine aggiunto al carrello';
    const ORDERDELETED = 'Ordine eliminato dal carrello';
    const PERSONALDATAUPDATED = 'Dati personali aggiornati con successo';
    const PRODDELETED = 'Prodotto cancellato';
    const PWDUPDATED = 'Password aggiornata';
    const USERUPDATED = 'Username aggiornato';
   
    //error messages
    const ERR_ALREALDYCART = 'Il prodotto selezionato è già presente nel carrello';
    const ERR_FORMINVALIDVALUE = 'Nessun valore valido inserito nel form';
    const ERR_INVALIDOPERATION = 'Operazione specificata non valida';
    const ERR_NOTLOGGED = 'Non sei collegato';
    const ERR_ORDERDELETEINVALIDID = 'Impossibile eliminare l\' ordine dal carrello perché l\'id non è valido';
    const ERR_ORDERINVALIDDATA = 'Impossibile aggiungere l\' ordine al carrello perché uno o più dati passati non sono validi';
    const ERR_PERSONALDATANOTUPDATED = 'ERRORE: Dati personali non aggiornati';
    const ERR_PRODINVALID = 'Nessun prodotto valido specificato';
    const ERR_PRODNOTDELETED = 'Prodotto non cancellato';
    const ERR_PWDCONFDIFFERENT = 'La password da sostituire non coincide con quella attuale';
    const ERR_PWDCURRENTWRONG = 'password attuale non corretta';
    const ERR_PWDNOTUPDATED = 'ERRORE: password non aggiornata';
    const ERR_USERNOTUPDATED = 'ERRORE: Username non aggiornato';
}
?>