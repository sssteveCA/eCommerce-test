<?php

namespace EcommerceTest\Interfaces;

//string messages constants
interface Messages{

    //success messages
    const EMAILRECOVERY = 'Una mail per il recupero della password è stata inviata alla tua casella di posta';
    const EMAILSENT1 = 'La mail è stata inviata correttamente al venditore';
    const EMAILSENT2 = 'Grazie per averci contattato! Le risponderemo il prima possibile';
    const ORDERADDEDCART = 'Ordine aggiunto al carrello';
    const ORDERDELETEDCART = 'Ordine eliminato dal carrello';
    const PERSONALDATAUPDATED = 'Dati personali aggiornati con successo';
    const PRODDELETED = 'Prodotto cancellato';
    const PWDUPDATED = 'Password aggiornata';
    const USERUPDATED = 'Username aggiornato';
   
    //error messages
    const ERR_ACTIVEACCOUNT = 'Attiva l\'account per poter accedere';
    const ERR_ALREALDYCART = 'Il prodotto selezionato è già presente nel carrello';
    const ERR_EMAILSENDING1 = 'Errore durante l\' invio della mail';
    const ERR_EMAILSENDING2 = 'C\'è stato un errore durante l\'invio della mail. Riprova più tardi';
    const ERR_FORMINVALIDVALUE = 'Nessun valore valido inserito nel form';
    const ERR_EMAILINSERT = 'Inserisci un indirizzo mail';
    const ERR_INVALIDDATA = 'Dati incompleti o non validi';
    const ERR_INVALIDOPERATION = 'Operazione specificata non valida';
    const ERR_INVALIDOPERATION2 = 'Non è stata scelta alcuna operazione valida';
    const ERR_NOTLOGGED = 'Non sei collegato';
    const ERR_ORDERDELETEINVALIDID = 'Impossibile eliminare l\' ordine dal carrello perché l\'id non è valido';
    const ERR_ORDERINVALIDDATA = 'Impossibile aggiungere l\' ordine al carrello perché uno o più dati passati non sono validi';
    const ERR_PERSONALDATANOTUPDATED = 'ERRORE: Dati personali non aggiornati';
    const ERR_PRODINVALID = 'Nessun prodotto valido specificato';
    const ERR_PRODNOTDELETED = 'Prodotto non cancellato';
    const ERR_PWDCONFDIFFERENT = 'La password da sostituire non coincide con quella attuale';
    const ERR_PWDCURRENTWRONG = 'password attuale non corretta';
    const ERR_PWDNOTUPDATED = 'ERRORE: password non aggiornata';
    const ERR_UNKNOWN = 'Errore sconosciuto';
    const ERR_USERNOTUPDATED = 'ERRORE: Username non aggiornato';
    const ERR_USERPWDWRONG = 'Email o password non corretti';
}
?>