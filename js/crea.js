$(function(){
    $('#fInserzione').on('submit',function(e){
        e.preventDefault();
        var form = $('#fInserzione')[0];
        var fData = new FormData(form);
        $.ajax({
            url : 'funzioni/upload.php',
            method : 'post',
            data : fData,
            contentType: false,
            processData: false,
            success: function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                //console.log(risp);
                message('Crea Inserzione','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                    if(risp.hasOwnProperty('ok')){
                        if(risp.ok == '1'){
                            window.location.href = 'inserzioni.php';
                        }
                    }
                });
            },
            error : function(xhr, stato, errore){

            },
            complete : function(xhr, stato){
    
            }
        });
    });//$('#fInserzione').on('submit',function(e){
});