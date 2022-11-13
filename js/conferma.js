$(function(){
    $('#cart').on('submit',function(ev){
        ev.preventDefault();
        var cDati = {};
        cDati['ajax'] = '1';
        cDati['oper'] = '2';
        cDati['ido'] = $('#ido').val();
        cDati['idp'] = $('#idp').val();
        $.ajax({
            url : 'funzioni/cartMan.php',
            method : 'post',
            headers : {
                'Accept': 'application/json', 'Content-Type': 'application/json'
            },
            data : JSON.stringify(cDati),
            success: function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Carrello','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            },
            error : function(xhr, stato, errore){
                /* console.log(xhr);
                console.log(stato);
                console.log(errore); */
                var risp = JSON.parse(xhr.responseText);
                message('Carrello','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            },
            complete : function(xhr, stato){
            }
        });
    });
});