import DialogMessageInterface from "../dialog/dialogmessage.interface";
import { showDialogMessage } from "../functions/functions";

function afterPaymentGet(data: any, actions: any, handleResponse: (result: any)=> void){
    var currentShippingVal = data.transactions[0].amount.details.shipping;
      currentShippingVal = parseFloat(currentShippingVal).toFixed(2);
      currentShippingVal = parseFloat(currentShippingVal);
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
        (<HTMLElement>document.querySelector('#paypalArea')).style.display = 'none';
        (<HTMLElement>document.querySelector('#confirm')).style.display = 'block';
        // Listen for click on confirm button
        (<HTMLButtonElement>document.querySelector('#confirmButton')).addEventListener('click', function() {
        // Disable the button and show a loading message
        (<HTMLElement>document.querySelector('#confirm')).innerText = 'Loading...';
        (<HTMLInputElement>document.querySelector('#confirm')).disabled = true;
        // Execute the payment
        var currency = (<HTMLInputElement>document.getElementById('currency'))?.value;
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

        })      
    // return actions.payment.execute().then(handleResponse);
    }

export function handleResponse(result: any) {
    // var resultDOM = document.getElementById('paypal-execute-details').textContent;
    // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);
    var resultDOM = JSON.stringify(result, null, 2);
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
    /*if(status == 'VERIFIED'){
        message('Pagamento','auto','400px','Il pagamento è stato completato con successo','close');
      
    }
    else{
        message('Pagamento','auto','400px','Errore durante il pagamento','close');
    }
    $('#dialog').on('dialogclose',function(){
            $('#dialog').remove();
        });*/
    $('#confirm').remove();
    var dati = {};
    dati['ajax'] = '1';
    dati['payer_status'] = status;
    dati['txn_id'] = transactionID;
    $.ajax({
        url : 'success.php',
        method : 'post',
        data : dati,
        success : function(risposta, stato, xhr){
            console.log(risposta);
            var risp = JSON.parse(risposta);
            let dmData: DialogMessageInterface = {
                title: 'Pagamento', message: risp['msg']
            }
            showDialogMessage(dmData);
        },
        error : function(xhr, stato, errore){
        },
        complete : function(xhr, stato){
        }
    });
}

export function paypalButton(paypal: any, clientId: string, handleResponse: (result: any)=> void){
    paypal.Button.render({
        // Set your environment
        env: 'sandbox', // sandbox | production
        funding: { allowed: [ paypal.FUNDING.CREDIT ] },
        client: { sandbox: clientId },
        style: {
              label: 'credit',
              size:  'medium', // small | medium | large | responsive
              shape: 'pill',  // pill | rect
        },


        // Wait for the PayPal button to be clicked

        payment: function(actions) {

            return paypalPayment(actions);
        },

        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data: any, actions: any) {

     return actions.payment.get().then(function(data) {       
        afterPaymentGet(data,actions,handleResponse);
         })   
       } 

    }, '#paypalArea');
}

function paypalPayment(actions: any){
    var currency = (<HTMLInputElement> document.getElementById('currency')).value;
            var shipping_amt: string|number = (<HTMLInputElement>document.getElementById('shipping')).value;
            shipping_amt = parseFloat(shipping_amt).toFixed(2);
            shipping_amt = parseFloat(shipping_amt);

            var subtotal: string|number = (<HTMLInputElement>document.getElementById('amount')).value;
            subtotal = parseFloat(subtotal).toFixed(2);
            subtotal = parseFloat(subtotal);

            var total_amt: any = subtotal + shipping_amt;
            total_amt = parseFloat(total_amt).toFixed(2);
            total_amt = parseFloat(total_amt);
            console.log("currency "+currency);
            console.log("shipping_amt "+shipping_amt);
            console.log("subtotal "+subtotal);
            console.log("total_amt "+total_amt);
            return actions.payment.create({
             meta: {
                 partner_attribution_id: '<?php echo(SBN_CODE)?>'
             },
             payment: {
                 payer: {
                        payment_method: 'paypal',
                        external_selected_funding_instrument_type: 'PAY_UPON_INVOICE'
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
                         }
                     }
                 ]
             }
            });
}