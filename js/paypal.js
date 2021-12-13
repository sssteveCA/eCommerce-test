
function showDom(id) {
    var arr;
    if (!Array.isArray(id)) {
        arr = [id];
    } else {
        arr = id;
    }
    arr.forEach(function (domid) {
        document.getElementById(domid).style.display = 'block';
    });
}

function hideDom(id) {
    var arr;
    if (!Array.isArray(id)) {
        arr = [id];
    } else {
        arr = id;
    }
    arr.forEach(function (domid) {
        document.getElementById(domid).style.display = 'none';
    });
}


function handleResponse(result) {
    console.log("handleResponse");

    document.getElementById('confirm').style.display ='none';
    // var resultDOM = document.getElementById('paypal-execute-details').textContent;
    // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);

    var resultDOM = JSON.stringify(result, null, 2);
    console.log(resultDOM);

    $json_response = result;
    // console.log($json_response['id']);
    var payID = $json_response['id'];

    var paymentState = $json_response['state'];
    var finalAmount = $json_response['transactions'][0]['amount']['total'];
    var currency = $json_response['transactions'][0]['amount']['currency'];
    var transactionID= $json_response['transactions'][0]['related_resources'][0]['sale']['id'];
    var payerFirstName = $json_response['payer']['payer_info']['first_name'];
    var payerLastName = $json_response['payer']['payer_info']['last_name'];
    var recipientName= $json_response['payer']['payer_info']['shipping_address']['recipient_name'],FILTER_SANITIZE_SPECIAL_CHARS;
    var addressLine1= $json_response['payer']['payer_info']['shipping_address']['line1'];
    var addressLine2 = $json_response['payer']['payer_info']['shipping_address']['line2'];
    var city= $json_response['payer']['payer_info']['shipping_address']['city'];
    var state= $json_response['payer']['payer_info']['shipping_address']['state'];
    var postalCode =$json_response['payer']['payer_info']['shipping_address']['postal_code'];
    var transactionType = $json_response['intent'];
    // var countryCode= filter_var($json_response['payer']['payer_info']['shipping_address']['country_code'],FILTER_SANITIZE_SPECIAL_CHARS);

    document.getElementById('paypal-execute-details-postal-code').textContent = postalCode; 
    document.getElementById('paypal-execute-details-state').textContent = state; 
    document.getElementById('paypal-execute-details-recipient-name').textContent = recipientName; 
    document.getElementById('paypal-execute-details-transaction-type').textContent = transactionType; 
    document.getElementById('paypal-execute-details-transaction-ID').textContent = transactionID; 
    document.getElementById('paypal-execute-details-first-name').textContent = payerFirstName; 
    // document.getElementById('paypal-execute-details-last-name').textContent = payerLastName; 
    document.getElementById('paypal-execute-details-payment-state').textContent = paymentState;
    document.getElementById('paypal-execute-details-final-amount').textContent = finalAmount; 
    document.getElementById('paypal-execute-details-currency').textContent = currency; 
    document.getElementById('paypal-execute-details-addressLine1').textContent = addressLine1;
    document.getElementById('paypal-execute-details-addressLine2').textContent = addressLine2;
    document.getElementById('paypal-execute-details-city').textContent = city;



    showDom('paypal-end');
    var button = document.getElementById('myContainer');
   /* button.link.style.display = 'none';
    var instructionNode = document.getElementById('instruction');
    instructionNode.style.display= 'none';*/
}
