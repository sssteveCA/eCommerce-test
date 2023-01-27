import DialogMessageInterface from "../dialog/dialogmessage.interface";
import { showDialogMessage } from "../functions/functions";

function handleResponse(result: any) {

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