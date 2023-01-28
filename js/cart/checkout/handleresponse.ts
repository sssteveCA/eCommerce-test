import DialogMessage from "../../dialog/dialogmessage.js";
import DialogMessageInterface from "../../dialog/dialogmessage.interface";
import GetCartOrdersInterface from "../interfaces/getcartorders.interface";
import { fGetCartOrders } from "../cart.js";
import { Constants } from "../../constants/constants.js";

export function handleResponse(result, clientId, idVend) {

    // var resultDOM = document.getElementById('paypal-execute-details').textContent;
    // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);

    var resultDOM = JSON.stringify(result, null, 2);
    /* console.log("resultDOM = ");
    console.log(resultDOM); */
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
    dati[Constants.KEY_AJAX] = '1';
    dati['payer_status'] = status;
    dati['txn_id'] = transactionID;
    dati['clientId'] = clientId;
    //console.log(dati);
    $.ajax({
        url : 'cartSuccess.php',
        method : 'post',
        data : dati,
        success : function(risposta, stato, xhr){
            //console.log(risposta);
            var risp = JSON.parse(risposta);
            //console.log(risp);
            let dm_data: DialogMessageInterface = {
                title: 'Pagamento ordini',
                message: risp[Constants.KEY_MESSAGE]
            }
            let dm: DialogMessage = new DialogMessage(dm_data);
            dm.btOk.on('click', ()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if(risp[Constants.KEY_DONE] === true){
                    //Refresh cart orders if payment was done successfully
                    let gco_data: GetCartOrdersInterface = {
                        operation: 1
                    }
                    fGetCartOrders(gco_data);
                }//if(risp[Constants.KEY_DONE] === true){
            });
        },
        error : function(xhr, stato, errore){
        },
        complete : function(xhr, stato){
        }
    });
}
