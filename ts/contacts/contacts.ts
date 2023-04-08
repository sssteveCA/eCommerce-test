import Contact from './contact.model';
import ContactRequest from './contact.request'

$(function () {
    $('#fContatti').on('submit', function (ev) {
        ev.preventDefault();
        var mess = {
            subject: $('#oggetto').val() as string,
            message: $('#messaggio').val() as string,
            ajax: true
        };
        let contact = new Contact(mess);
        let contactCtr = new ContactRequest(contact);
    });
});
