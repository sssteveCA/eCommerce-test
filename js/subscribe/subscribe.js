import SubscriberController from "./subscriber.controller.js";
import Subscriber from "./subscriber.model.js";
$(function () {
    $('#show').on('change', function () {
        //Show/hide password fields
        if ($(this).is(':checked')) {
            $('#password').attr('type', 'text');
            $('#confPass').attr('type', 'text');
        }
        else {
            $('#password').attr('type', 'password');
            $('#confPass').attr('type', 'password');
        }
    }); //$('#show').on('change',function(){
    $('#formReg').on('submit', function (e) {
        //Submit subscribe form
        e.preventDefault();
        var dati = {
            ajax: true,
            name: $('#nome').val(),
            surname: $('#cognome').val(),
            birth: $('#nascita').val(),
            sex: $('input[name="sesso"]:checked').val(),
            address: $('#indirizzo').val(),
            number: $('#numero').val(),
            city: $('#citta').val(),
            zip: $('#cap').val(),
            username: $('#user').val(),
            paypalMail: $('#paypalMail').val(),
            clientId: $('#clientId').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            confPass: $('#confPass').val()
        };
        let subscriber = new Subscriber(dati);
        let subscriberController = new SubscriberController(subscriber);
    }); //$('#formReg').on('submit',function(e){
}); //$(function(){
