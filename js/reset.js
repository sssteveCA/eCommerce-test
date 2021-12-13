$(function(){
    var dati = {};
    $('#fRecupera').on('submit',function(e){
        e.preventDefault();
        dati['ajax'] = '1';
        dati['chiave'] = $('#chiave').val();
        dati['nuova'] = $('#nuova').val();
        dati['confNuova'] = $('#confNuova').val();
        $.ajax({
            url : 'funzioni/pRecovery.php',
            method : 'post',
            data : dati,
            success : function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Recupero password','auto','400px',risp.msg,'destroy');
                //distruggo la finestra dopo averla chiusa
                $('#dialog').on('dialogclose',function(){
                    console.log("close");
                    $('#dialog').remove();
                    //redirect se la password è stata reimpostata
                    if(risp.hasOwnProperty('done')){
                        console.log("done");
                        window.location.href = 'accedi.php';
                    }
                })
            },
            error : function(xhr, stato, errore){
                console.error(errore);
            },
            complete : function(xhr, stato){
            }
        });
    });//$('#fRecupera').on('submit',function(e){
});