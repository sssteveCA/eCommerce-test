import Contact from './contact.model.js';
import ContactController from './contact.controller.js';
$(function () {
    $('#fContatti').on('submit', function (ev) {
        ev.preventDefault();
        var mess = {
            subject: $('#oggetto').val(),
            message: $('#messaggio').val(),
            ajax: true
        };
        let contact = new Contact(mess);
        let contactCtr = new ContactController(contact);
    });
});
