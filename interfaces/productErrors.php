<?php

//Error constants for class 'Prodotto'
namespace EcommerceTest\Interfaces;

interface ProductErrors{
    //number constants
    const INFONOTGETTED = 1;
    const IMGNOTCOPIED = 2;
    const QUERYERROR = 3;
    const DATANOTDELETED = 4;
    const DATANOTINSERTED = 5;
    const IDNOTEXIST = 6;

    //exception string constants
    const EXC_TABLECREATION = "Errore nella creazione dela tabella";
    const EXC_INVALIDDATA = "Il formato dei dati inseriti non è valido";

    //string constants
    const MSG_INFONOTGETTED = "Impossibile ottenere le informazioni sul prodotto dal database MySql";
    const MSG_IMGNOTCOPIED = "Il file immagine non è stato copiato";
    const MSG_QUERYERROR = "Query errata";
    const MSG_DATANOTDELETED = "Impossibile cancellare il prodotto selezionato";
    const MSG_DATANOTINSERTED = "Prodotto non inserito nel database";
    const MSG_IDNOTEXIST = "Questo prodotto non ha un ID";
}
?>