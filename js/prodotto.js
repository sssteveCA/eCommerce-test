$(function(){
    $('#formMail').on('submit',function(e){
        e.preventDefault();
        var dati = {};
        dati['ajax'] = '1';
        dati['oper'] = '3';
        dati['emailTo'] = $('#emailTo').val(); //destinatario del messaggio
        dati['pOggetto'] = $('#oggetto').val(); //oggetto del messaggio
        dati['pMessaggio'] = $('#messaggio').val(); //corpo del messaggio
        $.ajax({
            url : 'funzioni/mail.php',
            method : 'post',
            data : dati,
            success : function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Email venditore','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            },
            error : function(xhr, stato, errore){
                console.error(errore);
            },
            complete : function(xhr, stato){
            }
        });
    });//$('#formMail').on('submit',function(e){
    $('#elimina').on('submit',function(e){
        e.preventDefault();
        var dati = {};
        dati['idp'] = $('#idp').val();
        $.ajax({
            url : 'funzioni/elimina.php',
            method : 'post',
            data : dati,
            success : function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Inserzioni','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                    //se l'inserzione  stata eliminata senza problemi
                    if(risp.ok == '1'){
                        window.location.href = 'inserzioni.php';
                    }

                });
            },
            error : function(xhr, stato, errore){
                console.error(errore);
            },
            complete : function(xhr, stato){
            }
        });

    });//$('#elimina').on('submit',function(e){
});