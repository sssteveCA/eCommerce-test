$(function(){
    //l'utente vuole disconnettersi
    $('#logout a').on('click',function(e){
        e.preventDefault();
        DialogRedirect('Esci','Sei sicuro di voler abbandonare la sessione?','funzioni/logout.php');
    });
});