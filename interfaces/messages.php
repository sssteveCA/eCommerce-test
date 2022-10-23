<?php

namespace EcommerceTest\Interfaces;

//string messages constants
interface Messages{

    //success messages
    const ADVANCEDSEARCHEMPTY = 'La ricerca non ha prodotto alcun risultato';
    const CARTEMPTY = 'Il carrello è vuoto';
    const EMAILRECOVERY = 'Una mail per il recupero della password è stata inviata alla tua casella di posta';
    const EMAILSENT1 = 'La mail è stata inviata correttamente al venditore';
    const EMAILSENT2 = 'Grazie per averci contattato! Le risponderemo il prima possibile';
    const INSERTIONUPLOADED = 'Inserzione caricata correttamente';
    const ORDERADDEDCART = 'Ordine aggiunto al carrello';
    const ORDERAMOUNTUPDATED = 'Quantità ordine modificata con successo';
	const ORDERDELETED = 'Ordine cancellato con successo';
    const ORDERDELETEDCART = 'Ordine eliminato dal carrello';
    const ORDERINSERTEDCART = 'Ordine inserito nel carrello';
    const PERSONALDATAUPDATED = 'Dati personali aggiornati con successo';
    const PRODDELETED = 'Prodotto cancellato';
    const PWDUPDATED = 'Password aggiornata';
    const SUBSCRIBECOMPLETED = 'Registrazione completata con successo, attiva l\' account accedendo alla tua casella di posta';
    const USERUPDATED = 'Username aggiornato';
   
    //error messages
    const ERR_ACTIVEACCOUNT = 'Attiva l\'account per poter accedere';
    const ERR_ADVANCEDSEARCH = 'Errore durante l\'esecuione delle operazioni di ricerca';
    const ERR_ALREALDYCART = 'Il prodotto selezionato è già presente nel carrello';
    const ERR_CODEINVALD = 'Codice non valido';
    const ERR_DATEINVALID = 'La data inserita non è valida';
    const ERR_EMAILBUSINESSINVALID = 'La mail business non è valida';
    const ERR_EMAILINVALID = 'La mail che hai inserito non è valida';
    const ERR_EMAILINSERT = 'Inserisci un indirizzo mail';
    const ERR_EMAILSENDING1 = 'Errore durante l\' invio della mail';
    const ERR_EMAILSENDING2 = 'C\'è stato un errore durante l\'invio della mail. Riprova più tardi';
    const ERR_FORMINVALIDVALUE = 'Nessun valore valido inserito nel form';
    const ERR_GENDERINVALID = 'Il genere inserito non è valido';
    const ERR_INSERTIONFILENOTUPLOADED = 'Il file non è stato caricato';
    const ERR_INSERTIONNOTIMAGE = 'Il file caricato non è un\' immagine JPEG';
    const ERR_INSERTIONLIST = 'Errore durante il caricamento delle inserzioni dell\'utente';
    const ERR_INVALIDDATA = 'Dati incompleti o non validi';
    const ERR_INVALIDOPERATION1 = 'Operazione specificata non valida';
    const ERR_INVALIDOPERATION2 = 'Non è stata scelta alcuna operazione valida';
    const ERR_LOGIN1 = '<a href="index.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
    const ERR_LOGIN2 = '<a href="../index.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
    const ERR_MYSQLCONN = 'Errore durante la connessione a MySql';
    const ERR_MYSQLQUERY = 'Query errata';
    const ERR_NOINSERTIONUPLOADED = 'Non hai caricato alcun annuncio';
    const ERR_NOOPERATION = 'Nessuna operazione selezionata';
    const ERR_NOTLOGGED = 'Non sei collegato';
    const ERR_ORDERALREALDYCART = 'Ordine già presente nel carrello';
    const ERR_ORDERAMOUNTNOTPDATED = 'Errore durante l\' aggiornamento della quantità';
    const ERR_ORDERDELETEINVALIDID = 'Impossibile eliminare l\' ordine dal carrello perché l\'id non è valido';
    const ERR_ORDERINVALID = 'Ordine non valido';
    const ERR_ORDERINVALIDAMOUNT = 'Quantità non valida';
    const ERR_ORDERINVALIDDATA = 'Impossibile aggiungere l\' ordine al carrello perché uno o più dati passati non sono validi';
    const ERR_PERSONALDATANOTUPDATED = 'ERRORE: Dati personali non aggiornati';
    const ERR_PRODINVALID = 'Nessun prodotto valido specificato';
    const ERR_PRODNOTDELETED = 'Prodotto non cancellato';
    const ERR_PWDCONFDIFFERENT = 'La password da sostituire non coincide con quella attuale';
    const ERR_PWDCURRENTWRONG = 'password attuale non corretta';
    const ERR_PWDNOTEQUAL = 'Le due password non coincidono';
    const ERR_PWDNOTSETTED = 'Nessuna password impostata';
    const ERR_PWDNOTUPDATED = 'ERRORE: password non aggiornata';
    const ERR_PWDWRONG = 'Password errata';
    const ERR_REQUIREDFIELDSNOTFILLED = 'Uno o più campi obbligatori non sono stati compilati';
    const ERR_UNKNOWN = 'Errore sconosciuto';
    const ERR_USERNOTUPDATED = 'ERRORE: Username non aggiornato';
    const ERR_USERPWDWRONG = 'Email o password non corretti';

    //Other
    const ADMIN_CONTACT = 'Se il problema persiste contattare l\'amministratore del sito';
}
?>