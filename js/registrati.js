$(function(){
    $('#show').on('change',function(){
        if($(this).is(':checked')){
            $('#password').attr('type','text');
        }
        else{
            $('#password').attr('type','password');
        }
    });
    $('#formReg').on('submit',function(e){
        e.preventDefault();
        var dati = {};
        dati['ajax'] = '1';
        dati['nome'] = $('#nome').val();
        dati['cognome'] = $('#cognome').val();
        dati['nascita'] = $('#nascita').val();
        dati['sesso'] = $('input[name="sesso"]:checked').val();
        dati['indirizzo'] = $('#indirizzo').val();
        dati['numero'] = $('#numero').val();
        dati['citta'] = $('#citta').val();
        dati['cap'] = $('#cap').val();
        dati['user'] = $('#user').val();
        dati['paypalMail'] = $('#paypalMail').val();
        dati['clientId'] = $('#clientId').val();
        dati['email'] = $('#email').val();
        dati['password'] = $('#password').val();
        $.ajax({
            url : 'funzioni/nuovoAccount.php',
            method : 'post',
            data : dati,
            success : function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Registrazione account','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            },
            error : function(xhr, stato, errore){

            },
            complete : function(xhr, stato){
    
            }
        });
    });//$('#formReg').on('submit',function(e){
});