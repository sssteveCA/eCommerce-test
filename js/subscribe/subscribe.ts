import SubsciberInterface from "./data.interface.js";
import SubscriberController from "./subscriber.controller.js";
import Subscriber from "./subscriber.model.js";

$(function(){
    $('#show').on('change',function(){
        //Show/hide password fields
        if($(this).is(':checked')){
            $('#password').attr('type','text');
            $('#confPass').attr('type','text');
        }
        else{
            $('#password').attr('type','password');
            $('#confPass').attr('type','password');
        }
    });//$('#show').on('change',function(){

    $('#formReg').on('submit',function(e){
        //Submit subscribe form
        e.preventDefault();
        var dati: SubsciberInterface = {
            ajax: true,
            name: $('#nome').val() as string,
            surname: $('#cognome').val()as  string,
            birth: $('#nascita').val() as string,
            sex: $('input[name="sesso"]:checked').val() as string,
            address:  $('#indirizzo').val() as string,
            number: $('#numero').val() as number,
            city: $('#citta').val() as string,
            zip: $('#cap').val() as string,
            username: $('#user').val() as string,
            paypalMail: $('#paypalMail').val() as string,
            clientId: $('#clientId').val() as string,
            email: $('#email').val() as string,
            password: $('#password').val() as string,
            confPass: $('#confPass').val() as string
        };
        let subscriber = new Subscriber(dati);
        let subscriberController = new SubscriberController(subscriber);
    });//$('#formReg').on('submit',function(e){
});//$(function(){