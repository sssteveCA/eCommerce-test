//centro il div contenente il form del messaggio
function centerDiv(id){
    var div = $(id);
    var divLeft = ($(window).width()) / 2 - (div.outerWidth() / 2);
    var divTop = ($(window).height()) / 2 - (div.outerHeight() / 2);
    div.css({
        //position : 'absolute',
        left : divLeft+'px',
        top : divTop+'px'
    });
}


$(function(){
    centerDiv('#d1');
    /*il div che contiene il form del messaggio viene centrato ogni volta che
    la finestra del browser viene ridimensionata*/
    $(window).on('resize',function(){
        centerDiv('#d1');
    });
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