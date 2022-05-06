function chiamaAjax(dati){
    $.ajax({
        url : 'funzioni/editProfile.php',
        method : 'post',
        data : dati,
        success : function(risposta, stato, xhr){
            console.log(risposta);
            var risp = JSON.parse(risposta);
            if(risp.hasOwnProperty('msg')){
                //alert(risp.msg);
                message('Modifica profilo','auto','400px',risp.msg,'close');
                $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });
            }
            if(risp.hasOwnProperty('errore')){
                console.log(risp.errore);
            }
            if(risp.hasOwnProperty('query')){
                console.log(risp.query);
            }
            if(risp.hasOwnProperty('user')){
                $('#welcome').html('Benvenuto '+risp.user);
            }
        },
        error : function(xhr, stato, errore){

        },
        complete : function(xhr, stato){

        }
    });
}//function chiamaAjax(dati){

$(function(){
    var post = {};
    //modifica del nome utente
    $('#userEdit').on('submit',function(e){
        e.preventDefault();
        post['user'] = $('#user').val();
        post['username'] = $('#newUser').val();
        chiamaAjax(post);
    });
    //modifica della password
    $('#pwdEdit').on('submit',function(e){
        e.preventDefault();
        post['pwd'] = $('#pwd').val();
        post['oPwd'] = $('#oldPwd').val();
        post['nPwd'] = $('#newPwd').val();
        post['confPwd'] = $('#confPwd').val();
        chiamaAjax(post);
    });
    //modifica dei dati personali
    $('#dataEdit').on('submit',function(e){
        e.preventDefault();
        post['pers'] = $('#pers').val();
        post['nome'] = $('#nome').val();
        post['cognome'] = $('#cognome').val();
        post['indirizzo'] = $('#indirizzo').val();
        post['numero'] = $('#numero').val();
        post['citta'] = $('#citta').val();
        post['cap'] = $('#cap').val();
        post['paypalMail'] = $('#paypalMail').val();
        post['ClientId'] = $('#clientId').val();
        chiamaAjax(post);
    });
});