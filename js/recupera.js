$(function(){
    $('#fRecupera').on('submit',function(ev){
        ev.preventDefault();
        var dati = {};
        dati['email'] = $('#email').val();
        dati['ajax'] = '1';
        $.ajax({
            url : 'funzioni/mail.php',
            method : 'post',
            data : dati,
            success : function(risposta, stato, xhr){
                var ris = JSON.parse(risposta);
                //alert(ris.msg);
                message('Recupera account','auto','400px',ris.msg,'close');
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
    });
});