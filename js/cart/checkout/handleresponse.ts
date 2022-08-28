import GetCartOrdersInterface from "../interfaces/getcartorders.interface";
import GetCartOrders from "../requests/getcartorders";

function handleResponse(result, clientId, idVend) {

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
            let gco_data: GetCartOrdersInterface = {
                operation: 1
            }
            let gco: GetCartOrders = new GetCartOrders(gco_data);
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
