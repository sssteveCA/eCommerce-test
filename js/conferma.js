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
            data : cDati,
            success: function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                message('Carrello','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            },
            error : function(xhr, stato, errore){
            },
            complete : function(xhr, stato){
            }
        });
    });
});