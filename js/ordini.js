
var call = false; //esegue la chiamata ad Ajax evitando loop infiniti
var pagaForm = '';

//crea la tabella con la lista degli ordini e le opzioni su di essi
function tabella(dati){
    var table = $('<table>');
    table.attr('border','1');
    var pagato; //se la colonna MySql 'pagato' = '0' allora 'No', se 'pagato' = '1'
    var colonnaCarrello = false;
    for(i in dati){
        if(dati[i].carrello == '0')colonnaCarrello = true;
    }
    var html = '';
    var tr = $('<tr>');
    html += '<th>Id ordine</th>';
    html += '<th>Id prodotto</th>';
    html += '<th>Id venditore</th>';
    html += '<th>Data ordine</th>';
    html += '<th>Quantita</th>';
    html += '<th>Prezzo totale</th>';
    html += '<th>Ordine pagato</th>';
    html += '<th></th><th></th><th></th>';
    console.log(colonnaCarrello);
    if(colonnaCarrello)html += '<th></th>';
    //html += '<th id="ultima"></th>';
    tr.html(html);
    table.append(tr);
    var j = 1; //contatore per i form
    for(i in dati){
        if(i != 'tab' && i != 'i'){
            var riga = $('<tr>');
            html = '';
            html += '<td>'+dati[i].id+'</td>'; //id dell'ordine
            html += '<td>'+dati[i].idp+'</td>'; //id del prodotto
            html += '<td>'+dati[i].idv+'</td>'; //id del prodotto
            html += '<td>'+dati[i].data+'</td>'; //data in cui è stato creato l'ordine
            html += '<td class="tQuantita">';
            html += '<input type="number" id="q'+j+'" class="iQuantita" name="quantita" form="f'+j+'" value="'+dati[i].quantita+'">'; //quantità del singolo prodotto ordinate
            html += '<input type="submit" form="f'+j+'" class="bQuantita" name="bQuantita" value="MODIFICA">';
            html += '</td>';
            html += '<td>'+dati[i].totale+'€</td>'; //prezzo totale da pagare in Euro
            if(dati[i].pagato == '1')pagato = 'Sì'; 
            else pagato = 'No';
            html += '<td>'+pagato+'</td>'; //Ordine pagato o ancora da pagare
            /*form con tre pulsanti: 
            'DETTAGLI' : mostra le informazioni sul prodotto comprato
            'ELIMINA' : cancella definitivamente l'ordine
            'PAGA' : acquista il prodotto ordinato(se non ancora fatto)*/
            html += '<form id="f'+j+'" class="formOrder" method="get" action="orderMan.php"></form>';
            html += '<input type="hidden" form="f'+j+'" class="idOrd" name="idOrd" value="'+dati[i].id+'">';
            html += '<td><input type="submit" form="f'+j+'" class="bDettagli" name="bDettagli" value="DETTAGLI"></td>';
            html += '<td><input type="submit" form="f'+j+'" class="bElimina" name="bElimina" value="ELIMINA"></td>';
            if(colonnaCarrello){
                html += '<td>';
                if(dati[i]['carrello'] == '0'){
                    html += '<input type="submit" form="f'+j+'" class="bCarrello" name="bCarrello" value="CARRELLO">';
                }
                html += '</td>';
            }
            if(pagato == 'No'){
                var btnPaga = false; //true = mostra il bottone paga
                if(dati[i]['carrello'] == '1')btnPaga = true;
                html += '<td>';
                if(btnPaga == true){
                    var totale = parseFloat(dati[i].totale).toFixed(2);
                    totale = parseFloat(totale);
                    html += '<form id="pagaForm" method="post" action="conferma.php">';
                    html += '   <input type="hidden" id="idO" name="idO" value="'+dati[i].id+'">';
                    html += '   <input type="hidden" id="idC" name="idC" value="'+dati[i].idc+'">';
                    html += '   <input type="hidden" id="idP" name="idP" value="'+dati[i].idp+'">';
                    html += '   <input type="hidden" id="nP" name="nP" value="'+dati[i].quantita+'">';
                    html += '   <input type="hidden" id="nP" name="nP" value="'+dati[i].quantita+'">';
                    html += '   <input type="hidden" id="ord" name="ord" value="1">';
                    html += '   <input type="hidden" id="tot" name="tot" value="'+dati[i].totale+'">';
                    html += '   <input type="submit" value="PAGA">';
                    html += '</form>';
                }//if(btnPaga == true){
                html += '</td>';
                console.log(btnPaga);
            }//if(pagato == 'No'){
            else html += '<td></td>';
            j++;
            riga.html(html);
            table.append(riga);
        }
    }
    $('#ordiniT').append(table);
    $('.formOrder').on('submit',function(ev){
        ev.preventDefault();
        //ottengo il valore del pulsante dove è stato fatto il 'click'
        var btn = $('input[type=submit]:focus').val();
        var data = {};
        data['idOrd'] = $('input[type=hidden][form='+$(this).attr('id')+']').val();
        var ido = data['idOrd'];
        if(btn == 'DETTAGLI'){
            data['oper'] = '1';
            chiamaAjax(data);
        }
        else if(btn == 'ELIMINA'){
            //call = true;
            data['oper'] = '2';
            chiamaAjax(data);
        }
        else if(btn == 'MODIFICA'){
            data['oper'] = '3';
            data['quantita'] = $('input[name=quantita][form='+$(this).attr('id')+']').val();
            chiamaAjax(data);
        }
        else if(btn == 'CARRELLO'){
            data['oper'] = '4';
            chiamaAjax(data);
        }
    });//$('.formOrder').on('submit',function(ev){
}//function tabella(dati){
/*tramite ajax chiedo gli ordini effettuati dall'utente
dati = variabili da passare alla richiesta GET
op = operazione da eseguire(richiesta ordini eseguiti, dettagli ordini, cancella ordine)*/
function chiamaAjax(dati){
    $.ajax({
        url : 'funzioni/orderMan.php',
        method : 'get',
        data : dati,
        success : function(risposta, stato, xhr){
            //console.log(risposta);
            var risp = JSON.parse(risposta);
            //console.log(risp);
            if(risp.length === 0){
                //alert("Si è verificato un errore nella lettura degli ordini");
                message('Gestione ordini','auto','400px','Si è verificato un errore nella lettura degli ordini','close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            }
            else{
                if(risp.hasOwnProperty('msg')){
                    console.log('msg');
                    //alert(risp.msg);
                    message('Gestione ordini','auto','400px',risp.msg,'close');
                    $('#dialog').on('dialogclose',function(){
                        $('#dialog').remove();
                    });
                }
                //se sono stati chiesti i dettagli dell'ordine
                if(risp.hasOwnProperty('info')){
                    var html = 'Dati venditore<br>';
                    html += 'Nome: '+risp.nome+'<br>';
                    html += 'Cognome: '+risp.cognome+'<br>';
                    html += 'Nato il: '+risp.nascita+'<br>';
                    html += 'Residente a: '+risp.citta+'<br>';
                    html += 'Indirizzo: '+risp.indirizzo+', '+risp.numero+'<br>';
                    html += 'CAP: '+risp.cap+'<br>';
                    html += 'Indirizzo Email: '+risp.email+'<br><br>';
                    html += 'Dati Prodotto<br>';
                    html += 'Nome: '+risp.nomeP+'<br>';
                    html += 'Categoria: '+risp.tipo+'<br>';
                    html += 'Prezzo: '+risp.prezzo+'€<br>';
                    html += 'Spedizione: '+risp.spedizione+'€<br>';
                    html += 'Quantita: '+risp.quantita+'<br>';
                    html += 'Spedito da: '+risp.stato+', '+risp.citta+'<br>';
                    html += 'Totale: '+risp.totale+'€';
                    message('Informazioni sull\' ordine','auto','400px',html,'close');
                    $('#dialog').on('dialogclose',function(){
                        $('#dialog').remove();
                    });
                }//if(risp.hasOwnProperty('info')){
                //se l'ordine scelto è stato cancellato
                if(risp.hasOwnProperty('aggiorna')){
                    if(risp.aggiorna == '1'){
                        var data = {};
                        data['oper'] = '0';
                        //aggiorna la tabella quando la chiamata AJAX precedente è terminata
                        $('#ordiniT').html('');
                        chiamaAjax(data);
                    }
                }
                //array che contiene l'elenco degli ordini effettuato dall'utente
                if(risp.hasOwnProperty('tab') && risp.hasOwnProperty('i')){
                    if(risp.tab == '1' && risp.i > 0){
                        $('#ordiniT').html('');
                        tabella(risp);
                    }
                    else{
                        var pr = $('<p>');
                        pr.html('Nessun ordine effettuato');
                        pr.css({
                            'text-align' : 'center',
                            'font-size' : '22px',
                            'font-weight' : 'bold'
                        });
                        $('#ordiniT').append(pr);
                    }
                }
            }
        },
        error : function(xhr, stato, errore){

        },
        complete : function(xhr, stato){
        }
    });
}

$(function(){
    var dati = {};
    dati['oper'] = '0'; //richiesta degli ordini effettuati
    chiamaAjax(dati);
});