import EditUser from "./edituser.model.js";
import EditUserController from "./edituser.controller.js";
$(function () {
    let post = {};
    //Edit username form
    $('#userEdit').on('submit', function (e) {
        e.preventDefault();
        post['action'] = 1;
        post['user'] = $('#user').val();
        post['username'] = $('#newUser').val();
        post['ajax'] = true;
        try {
            let editUser = new EditUser(post);
            let editUserCtr = new EditUserController(editUser);
        }
        catch (e) {
            console.warn(e);
        }
    });
    //Edit password form
    $('#pwdEdit').on('submit', function (e) {
        e.preventDefault();
        post['action'] = 2;
        post['pwd'] = $('#pwd').val();
        post['oldPassword'] = $('#oldPwd').val();
        post['newPassword'] = $('#newPwd').val();
        post['confPassword'] = $('#confPassword').val();
        post['ajax'] = true;
        try {
            let editUser = new EditUser(post);
            let editUserCtr = new EditUserController(editUser);
        }
        catch (e) {
            console.warn(e);
        }
    });
    //Edit personal data form
    $('#dataEdit').on('submit', function (e) {
        e.preventDefault();
        post['action'] = 3;
        post['pers'] = $('#pers').val();
        post['name'] = $('#nome').val();
        post['surname'] = $('#cognome').val();
        post['address'] = $('#address').val();
        post['number'] = $('#numero').val();
        post['city'] = $('#citta').val();
        post['cap'] = $('#cap').val();
        post['paypalMail'] = $('#paypalMail').val();
        post['clientId'] = $('#clientId').val();
        try {
            let editUser = new EditUser(post);
            let editUserCtr = new EditUserController(editUser);
        }
        catch (e) {
            console.warn(e);
        }
    });
});
