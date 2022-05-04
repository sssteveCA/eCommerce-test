import ContactInterface from './data.interface';
import Contact from './contact.model';
import ContactController from './contact.controller';


$(function () {
    $('#fContatti').on('submit', function (ev) {
        ev.preventDefault();
        var mess: ContactInterface = {
            subject: $('#oggetto').val() as string,
            message: $('#messaggio').val() as string,
            ajax: true
        };
        let contact = new Contact(mess);
        let contactCtr = new ContactController(contact);
    });
});
