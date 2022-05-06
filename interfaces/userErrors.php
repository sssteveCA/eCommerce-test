<?php

//Error constants for class 'Utente'
namespace EcommerceTest\Interfaces;

interface UserErrors{

    //number constants
    const INCORRECTLOGINDATA = 1;
    const ACTIVEYOURACCOUNT = 2;
    const CONNECTFAIL = 3;
    const DATANOTUPDATED = 4;
    const DATANOTINSERTED = 5;
    const QUERYERROR = 6;
    const USERNAMEMAILEXIST = 7;
    const ACCOUNTNOTACTIVATED = 8;
    const MAILNOTSENT = 9;
    const INVALIDFIELD = 10;
    const DATANOTSET = 11;
    const ACCOUNTNOTRECOVERED = 12;
    const INVALIDDATAFORMAT = 13;

    //exceptions string constants
    const EXC_TABLECREATION = "Errore nella creazione dela tabella";
    const EXC_INVALIDDATA = "I dati forniti non sono validi";
    const EXC_MYSQLCONN = "Connessione a MySQL fallita";

    //string constants
    const MSG_INCORRECTLOGINDATA = "Email o password non corretti";
    const MSG_ACTIVEYOURACCOUNT = "Per poter accedere devi attivare l'account";
    const MSG_CONNECTFAIL = "Connessione a MySql fallita";
    const MSG_DATANOTUPDATED = "Dati non aggiornati";
    const MSG_DATANOTINSERTED = "Dati registrazione non inseriti nel database";
    const MSG_QUERYERROR = "Query errata";
    const MSG_USERNAMEMAILEXIST = "Lo username o la mail inserita esistono già";
    const MSG_ACCOUNTNOTACTIVATED = "Attivazione account non riuscita";
    const MSG_MAILNOTSENT = "Email non inviata";
    const MSG_INVALIDFIELD = "Non è stato specificato un campo per fare la selezione dei dati oppure non è un campo valido";
    const MSG_DATANOTSET = "Uno o più dati richiesti non sono stati settati";
    const MSG_ACCOUNTNOTRECOVERED = "Impossibile recuperare l'account";
    const MSG_INVALIDDATAFORMAT = "Uno o più parametri non sono nel formato corretto";

}
?>