
import * as hr from './handleresponse';

declare var paypal:any;
/**
sbn = SBN_CODE,
cId = ID venditore App Paypal
curr = valuta utilizzata
subtotal = prezzo del prodotto
shipping_amt = spese di spedizione
 */
function paypalCheckout(sbn,cId,currency,venditore,idVend){
    var prodotti:any = [];
    var prezzo;
    for(let i in venditore){
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
            for(let i in venditore){
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
            var total_amt:any = subtotal + shipping_amt;
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
                        let current_pa: HTMLElement|null = document.querySelector('#paypalArea'+idVend);
                        if(current_pa != null)
                            current_pa.style.display = 'none';
                        let current_confirm: HTMLButtonElement|null = document.querySelector('#confirm'+idVend);
                        if(current_confirm != null)
                            current_confirm.style.display = 'block';
                        // Listen for click on confirm button
                        let current_cb: HTMLElement|null = document.querySelector('#confirmButton'+idVend);
                        if(current_cb != null){
                            current_cb.addEventListener('click', function(ev) {
                            //id del pulsante premuto
        
                            // Disable the button and show a loading message
                            let current_confirm: HTMLButtonElement|null = document.querySelector('#confirm'+idVend);
                            if(current_confirm != null)
                            {
                                current_confirm.innerText = 'Loading...';
                                current_confirm.disabled = true;
                            }
                            
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
                             }).then(hr.handleResponse);
                            /*}).then(function(){
                                //redirect a 'cartSuccess.php'
                                actions.redirect();
                            });*/
        
                            });//fine AddEventListener #confirmButton  
                        }
                            
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