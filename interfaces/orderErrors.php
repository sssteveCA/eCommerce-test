<?php

//Error constants for class 'Ordine'
namespace EcommerceTest\interfaces;

interface OrderErrors{

    //number constants
    const INFONOTGETTED = 1;
    const QUERYERROR = 2;
    const DATANOTINSERTED = 3;
    const DATANOTDELETED = 4;
    const NOTADDEDCART = 5;
    const IDNOTEXISTS = 6;
    const NOTDELETEDCART = 7;

    //string constants
    const MSG_INFONOTGETTED = "Impossibile ottenere le informazioni sull'ordine dal database MySql";
    const MSG_QUERYERROR = "Query errata";
    const MSG_DATANOTINSERTED = "Errore durante l'inserimento dei dati nella tabella MySql";
    const MSG_DATANOTDELETED = "Nessun ordine cancellato";
    const MSG_NOTADDEDCART = "Nessun ordine aggiunto al carrello";
    const MSG_IDNOTEXISTS = "Id nell'oggetto Ordine non presente";
    const MSG_NOTDELETEDCART = "Nessun ordine cancellato dal carrello";

}
?>