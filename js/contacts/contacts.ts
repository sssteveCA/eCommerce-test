import Contact from './contact.model.js';
import ContactController from './contact.controller.js'

$(function () {
    $('#fContatti').on('submit', function (ev) {
        ev.preventDefault();
        var mess = {
            subject: $('#oggetto').val() as string,
            message: $('#messaggio').val() as string,
            ajax: true
        };
        let contact = new Contact(mess);
        let contactCtr = new ContactController(contact);
    });
});
