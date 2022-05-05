
$(function () {
    $('#fContatti').on('submit', function (ev) {
        ev.preventDefault();
        var mess = {
            subject: $('#oggetto').val(),
            message: $('#messaggio').val(),
            ajax: true
        };
        let contact = new window.Contact(mess);
        let contactCtr = new window.ContactController(contact);
    });
});
