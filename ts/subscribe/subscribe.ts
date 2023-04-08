import DialogMessageInterface from "../dialog/dialogmessage.interface.js";
import DialogMessage from "../dialog/dialogmessage.js";
import SubsciberInterface from "./subscriber.interface.js";
import SubscriberRequest from "./subscriber.request.js";
import Subscriber from "./subscriber.model.js";
import { showDialogMessage } from "../functions/functions.js";
import { Constants } from "../constants/constants.js";

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
        let spinner: JQuery<HTMLDivElement> = $('#spinner') as JQuery<HTMLDivElement>;
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
        let sr = new SubscriberRequest(subscriber);
        spinner.toggleClass("invisible");
        sr.subscribeRequest().then(obj => {
            spinner.toggleClass("invisible");
            let dmData: DialogMessageInterface = {
                title: "Registrazione", message: obj[Constants.KEY_MESSAGE]
            };
            showDialogMessage(dmData);
        });
    });//$('#formReg').on('submit',function(e){
});//$(function(){