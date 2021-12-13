var sbn = '';
var datiG = {};
var currency = 'EUR';
var clientId = '';
var idVend = '';

function tabella(){
    var html = '';
    $('#carrello').html('');
    var table = $('<table>');
    //table.attr('border','1');
    var tr = $('<tr>');
    html += '<th>Nome</th>';
    html += '<th>Immagine</th>';
    html += '<th>Tipo</th>';
    html += '<th>Quantità</th>';
    html += '<th>Totale</th>';
    html += '<th></th><th></th>';
    var numCol=7;
    tr.html(html);
    table.append(tr);
    
    for(var idv in datiG.carrello){
        var seller = datiG.carrello[idv];
        for(var i in seller) {
            html = '';
            tr = $('<tr>');
            html += '<td>';
            html += seller[i].nome;
            //html += '<input type="hidden" name="name[]" form="fCarrello" value="'+dati[idv][i].nome+'">';
            html += '</td>';
            html += '<td class="timg"><img src="'+seller[i].immagine+'"></td>';
            html += '<td>'+seller[i].tipo+'</td>';
            html += '<td>'+seller[i].quantita+'</td>';
            html += '<td>'+seller[i].totale+'€</td>';
            html += '<td>';
            html += '   <form id="fDett" method="get" action="prodotto.php">';
            html += '       <input type="hidden" name="id" value="'+seller[i].idp+'">';
            html += '       <input type="submit" class="iDett" id="bDett'+i+'" value="DETTAGLI">';
            html += '   </form>';
            html += '</td>';
            html += '<td>';
            html += '   <form class="fElim" id="fEl'+i+'" method="post" action="funzioni/cartMan.php">';
            html += '       <input type="hidden" name="oper" value="3">'; //elimina ordine dal carrello
            html += '       <input type="hidden" name="ido" value="'+seller[i].ido+'">';
            html += '       <input type="hidden" name="idv" value="'+idv+'">';
            html += '       <input type="submit" class="iElim" id="bElim'+i+'" value="ELIMINA">';
            html += '   </form>';
            html += '</td>';
            tr.html(html);
            table.append(tr);
        }
        tr = $('<tr>');
        td = $('<td>').attr({'colspan':numCol});
        td.html(divCarrello(idv));
        tr.html(td);
        table.append(tr);
    }//for(var idv in datiG.carrello){
    $('#carrello').append(table);
    $('.confirmButton').on('click',function(ev){
        var inputId = ev.target.id;
        var regex = /([0-9]+)$/;
        var match = regex.exec(inputId);
        idVend = match[1];
        clientId = datiG['carrello'][idVend][0]['clientId'];
        console.log("idv = "+idVend);
        paypalCheckout(datiG['sbn'],clientId,currency,datiG['carrello'][idVend],idVend);
    });
    $('form.fElim').on('submit',function(ev){
        ev.preventDefault();
        //ID dell'ordine da togliere
        var ido = $(this).children('input[name=ido]').val();
        console.log(ido);
        var cDati = {};
        cDati['ajax'] = '1';
        cDati['oper'] = '3'; //elimino l'ordine dal carrello
        cDati['ido'] = ido;
        chiamaAjax(cDati);
    });
}

//form per pagare gli ordini nel carrello
function divCarrello(id){
    var div = $('<div>');
    div.attr({
        id : 'divCarrello'+id
        //action : 'funzioni/payCart.php'
    });
    var iOper = $('<input>'); //input hidden che indica l'operazione che 'CartMan.php' dovrà fare
    var paypalArea = $('<div>');
    paypalArea.attr({
        class : 'paypalArea',
        id : 'paypalArea'+id
    });
    var confirm = $('<div>');
    confirm.attr({
        class : 'confirm',
        id : 'confirm'+id
    });
    var confirmButton = $('<button>');
    confirmButton.attr({
        class : 'confirmButton',
        id : 'confirmButton'+id
    });
    confirmButton.html('PAGA ORDINI');
    confirm.append(confirmButton);

    div.css({
        padding : '10px',
        display : 'flex',
        'justify-content' : 'center',
        'align-items' : 'center'
    });
    //form.append(iOper);
    //form.append(inputV);
    div.append(paypalArea);
    div.append(confirm);
   // $('#carrello').append(form);
   return div;
}

function chiamaAjax(dati){
    $.ajax({
        url : 'funzioni/cartMan.php',
        method : 'post',
        data : dati,
        success: function(risposta, stato, xhr){
            //console.log(risposta);
            var risp = JSON.parse(risposta);
            //console.log(risp);
            //nessun ordine nel carrello
            if(risp.hasOwnProperty('vuoto')){
                if(risp.vuoto == '0'){
                    datiG = {};
                    console.log("DatiG");
                    datiG = risp;
                    console.log(datiG);
                    tabella();
                }
                else{
                    $('#carrello').html('');
                    var par = $('<p>');
                    par.attr('id','null');
                    par.html('Nessun ordine nel carrello');
                    $('#carrello').append(par);

                }
            }
            if(risp.hasOwnProperty('msg')){
                message('Carrello','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            }
            if(risp.hasOwnProperty('del')){
                //se la cancellazione dell'ordine scelto è andato a buon fine
                if(risp.del == '1'){
                    //aggiorna la tabella
                    var cDati = {};
                    cDati['ajax'] = '1';
                    cDati['oper'] = '1'; //informazioni sugli ordini nel carrello
                    chiamaAjax(cDati);
                }
            }
        },
        error : function(xhr, stato, errore){

        },
        complete : function(xhr, stato){

        }

    });
}

function handleResponse(result) {

    // var resultDOM = document.getElementById('paypal-execute-details').textContent;
    // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);

    var resultDOM = JSON.stringify(result, null, 2);
    console.log("resultDOM = ");
    console.log(resultDOM);
    //var parseDOM = JSON.parse(resultDOM);
    //console.log(parseDOM);

    //$json_response = result;
    // console.log(result['id']);
    var payID = result['id'];

    var paymentState = result['state'];
    var finalAmount = result['transactions'][0]['amount']['total'];
    var currency = result['transactions'][0]['amount']['currency'];
    var transactionID= result['transactions'][0]['related_resources'][0]['sale']['id'];
    var status = result['payer']['status'];
    var payerFirstName = result['payer']['payer_info']['first_name'];
    var payerLastName = result['payer']['payer_info']['last_name'];
    var recipientName= result['payer']['payer_info']['shipping_address']['recipient_name'],FILTER_SANITIZE_SPECIAL_CHARS;
    var addressLine1= result['payer']['payer_info']['shipping_address']['line1'];
    var addressLine2 = result['payer']['payer_info']['shipping_address']['line2'];
    var city= result['payer']['payer_info']['shipping_address']['city'];
    var state= result['payer']['payer_info']['shipping_address']['state'];
    var postalCode =result['payer']['payer_info']['shipping_address']['postal_code'];
    var transactionType = result['intent'];
    // var countryCode= filter_var(result['payer']['payer_info']['shipping_address']['country_code'],FILTER_SANITIZE_SPECIAL_CHARS);
    //elimino la scritta 'Aspetta...' una volta che il pagamento è stato completato
    $('#confirm'+idVend).remove();
    var dati = {};
    dati['ajax'] = '1';
    dati['payer_status'] = status;
    dati['txn_id'] = transactionID;
    dati['clientId'] = clientId;
    $.ajax({
        url : 'cartSuccess.php',
        method : 'post',
        data : dati,
        success : function(risposta, stato, xhr){
            console.log(risposta);
            var risp = JSON.parse(risposta);
            console.log(risp);
            var oper = {};
            //aggiorno gli ordini nel carrello
            oper['ajax'] = '1';
            oper['oper'] = '1';
            chiamaAjax(oper);
            message('Carrello','auto','400px',risp['msg'],'close');
            $('#dialog').on('dialogclose',function(){
                $('#dialog').remove();
            });
        },
        error : function(xhr, stato, errore){
        },
        complete : function(xhr, stato){
        }
    });
}

/**
sbn = SBN_CODE,
cId = ID venditore App Paypal
curr = valuta utilizzata
subtotal = prezzo del prodotto
shipping_amt = spese di spedizione
 */
function paypalCheckout(sbn,cId,currency,venditore,idVend){
    var prodotti = [];
    var prezzo;
    for(i in venditore){
        prodotti[i] = {};
        /*inserisco le informazioni sui prodotti del venditore, che verranno poi aggiunte al JSON*/
        prodotti[i].name = venditore[i]['nome'];
        prodotti[i].quantity = parseInt(venditore[i]['quantita']);
        venditore[i]['prezzo'] = parseFloat(venditore[i]['prezzo']).toFixed(2);
        prezzo = parseFloat(venditore[i]['prezzo']);
        venditore[i]['prezzo'] = prezzo * prodotti[i].quantity;
        prodotti[i].price = prezzo;
        prodotti[i].currency = currency;
    }
    var prJSON = JSON.stringify(prodotti);
    console.log(prodotti);
    var clientId = cId;
        console.log("clientId = "+clientId);
        var client = {
            sandbox:  clientId
        };
        var environment = 'sandbox';
        paypal.Button.render({

            // Set your environment
    
            env: 'sandbox', // sandbox | production
            funding: {
                  allowed: [ paypal.FUNDING.CREDIT ]
            },
    
            client: {
                sandbox: clientId
            },
            
            style: {
                  label: 'credit',
                  size:  'medium', // small | medium | large | responsive
                  shape: 'pill',  // pill | rect
            },
            // Wait for the PayPal button to be clicked

        payment: function(actions) {
            var subtotal = 0;
            var shipping_amt = 0;
            for(i in venditore){
                //somma dei prezzi dei prodotti del venditore
                subtotal += venditore[i].prezzo;
                //somma delle spese di spedizione dei prodotti del venditore
                venditore[i].spedizione = parseFloat(venditore[i].spedizione).toFixed(2);
                venditore[i].spedizione = parseFloat(venditore[i].spedizione);
                shipping_amt += venditore[i].spedizione;
            }
            //var shipping_amt = document.getElementById('shipping').value;
            
            //var subtotal = document.getElementById('amount').value;
            
            //totale da pagare
            var total_amt = subtotal + shipping_amt;
            total_amt = parseFloat(total_amt).toFixed(2);
            total_amt = parseFloat(total_amt);
            console.log("currency "+currency);
            console.log("shipping_amt "+shipping_amt);
            console.log("subtotal "+subtotal);
            console.log("total_amt "+total_amt);
            return actions.payment.create({
             meta: {
                 partner_attribution_id: sbn
             },
             payment: {
                 payer: {
                        payment_method: 'paypal',
                        external_selected_funding_instrument_type: 'PAY_UPON_INVOICE'
                    },
                    redirect_urls : {
                        return_url : 'http://localhost/php/curl/accounts/cartSuccess.php',
                        cancel_url : 'http://localhost/php/curl/accounts/cartCancel.php',
                    },
                    transactions: [
                        {
                            amount: {
                                total: total_amt ,
                                //total: 1000.12 ,
                                currency: currency,
                                details:
                                {
                                    subtotal: subtotal,
                                    //subtotal: 990.12,
                                    shipping: shipping_amt,
                                    //shipping: 10,
                                }
                            },
                            //lista dei prodotti da comprare
                            /*item_list: {
                                items : prodotti
                            }*/
                        }
                    ]    
                }// fine payment
            });
        },
        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data, actions) {

            return actions.payment.get().then(function(data) {       
       
            console.log($('#confirmButton'+idVend));
            $('#confirmButton'+idVend).css({
                padding: '10px',
                'font-size' : '20px'
            });
            /* quando il pagamento è stato autorizzato il pulsante cambia testo
            da PAGA ORDINI => CONFERMA*/
            $('#confirmButton'+idVend).html('CONFERMA');
             var currentShippingVal = data.transactions[0].amount.details.shipping;
             currentShippingVal = parseFloat(currentShippingVal).toFixed(2);
             currentShippingVal = parseFloat(currentShippingVal);
             /*console.log("onAutorize");
             console.log(data);*/
             var shipping = data.payer.payer_info.shipping_address;
       
             var currentTotal = data.transactions[0].amount.total;
             currentTotal = parseFloat(currentTotal).toFixed(2);
             currentTotal = parseFloat(currentTotal);
       
                       console.log(shipping.recipient_name);
                       console.log(shipping.line1);
                       console.log(shipping.city);
                       console.log(shipping.state);
                       console.log(shipping.postal_code);
                       console.log(shipping.country_code);
       
                        console.log(currentShippingVal);
       
                       //total_amt =+ total_amt + shipping_amt_updated;
                        console.log(document.querySelector('#paypalArea'+idVend));
                        console.log(document.querySelector('#confirm'+idVend));
                       document.querySelector('#paypalArea'+idVend).style.display = 'none';
                       document.querySelector('#confirm'+idVend).style.display = 'block';
                        // Listen for click on confirm button
                        document.querySelector('#confirmButton'+idVend).addEventListener('click', function(ev) {
                            //id del pulsante premuto
        
                            // Disable the button and show a loading message
        
                            document.querySelector('#confirm'+idVend).innerText = 'Loading...';
                            document.querySelector('#confirm'+idVend).disabled = true;
        
                            // Execute the payment
                            var subtotal = currentTotal - currentShippingVal;
        
                            return actions.payment.execute(
                            {
                            transactions: [
                                {
                                    amount: {
                                        //total: 1000.12,
                                        total: currentTotal,
                                        currency: currency,
                                        details: 
                                        {
                                            subtotal: subtotal,
                                            //subtotal: 990.12,
                                            shipping: currentShippingVal,
                                            //shipping: 10,
                                        }
                                    }
                                }
                            ]    
                             }).then(handleResponse);
                            /*}).then(function(){
                                //redirect a 'cartSuccess.php'
                                actions.redirect();
                            });*/
        
                        })//fine AddEventListener #confirmButton  
                    })   //fine return actions.payment.execute().then(handleResponse);
              }, //fine onAuthorize
              //il cliente cancella il pagamento
              onCancel : function(data, actions){
                  //redirect alla pagina 'cartCancel.php'
                  actions.redirect();
              },

              //errore durante il pagamento
              onError : function(err){
              }
       
           }, /*'#paypalArea'*/'#paypalArea'+idVend);
}

$(function(){
    var cDati = {};
    cDati['ajax'] = '1';
    cDati['oper'] = '1'; //informazioni sugli ordini nel carrello
    chiamaAjax(cDati);
    
});