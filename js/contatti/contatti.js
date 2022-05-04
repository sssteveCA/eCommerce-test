


$(function(){
    $('#fContatti').on('submit',function(ev){
        var mess = {};
        mess['oggetto'] = $('#oggetto').val();
        mess['messaggio'] = $('#messaggio').val();
        $mess['ajax'] = '1';
        ev.preventDefault();
        $.ajax({
            url : 'funzioni/mail.php',
            method : 'post',
            data : mess,
            success : function(risposta, stato, xhr){
                console.log(risposta);
                var risp = JSON.parse(risposta);
                //alert(risp.msg);
                message('Contatti','auto','400px',risp.msg);
            },
            error : function(xhr, stato, errore){

            },
            complete : function(xhr, stato){
    
            }
        });
    });
});