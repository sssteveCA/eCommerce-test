


$(function(){
    $('#fContatti').on('submit',function(ev){
        ev.preventDefault();
        var mess = {};
        mess['oggetto'] = $('#oggetto').val();
        mess['messaggio'] = $('#messaggio').val();
        mess['ajax'] = true;
        let contact = new Contact(mess);
        let contactCtr = new ContactController(contact);
    });
});