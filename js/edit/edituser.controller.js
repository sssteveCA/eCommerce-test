var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import EditUser from "./edituser.model.js";
import DialogMessage from "../dialog/dialogmessage.js";
export default class EditUserController {
    constructor(editUser) {
        this._editUser = editUser;
        this._errno = 0;
        this._error = null;
        if (this._editUser) {
            //editUser is not null
            switch (this._editUser.action) {
                case EditUser.ACTION_USERNAME:
                    this.editUsername();
                    break;
                case EditUser.ACTION_PASSWORD:
                    this.editPassword();
                    break;
                case EditUser.ACTION_PERSONALDATA:
                    this.editPersonalData();
                    break;
                default:
                    this._errno = EditUserController.ERR_INVALIDACTION;
                    break;
            }
        }
        else
            this._errno = EditUserController.ERR_NOEDITUSEROBJECT;
    }
    get editUser() { return this._editUser; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case EditUserController.ERR_NOEDITUSEROBJECT:
                this._error = EditUserController.ERR_MSG_NOEDITUSEROBJECT;
                break;
            case EditUserController.ERR_DATAMISSED:
                this._error = EditUserController.ERR_MSG_DATAMISSED;
                break;
            case EditUserController.ERR_INVALIDACTION:
                this._error = EditUserController.ERR_MSG_INVALIDACTION;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    //validate editUsername data
    validateEditUsername() {
        let ok = false;
        if (this._editUser.username && typeof (this._editUser.isAjax) != "undefined") {
            ok = true;
        }
        return ok;
    }
    //User edits his username
    editUsername() {
        if (this.validateEditUsername()) {
            let jsonRes;
            this.editUsernamePromise().then(res => {
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica nome utente', jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica nome utente', err);
            });
        }
        else
            EditUserController.ERR_DATAMISSED;
    }
    //Do the edit Username action
    editUsernamePromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                const data = {
                    user: '1',
                    username: this._editUser.username
                };
                const params = {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    }
                };
                const response = fetch(EditUserController.EDITPROFILE_URL, params);
                response.then(res => {
                    resolve(res.text());
                }).catch(err => {
                    console.warn(err);
                    reject(EditUserController.ERR_MSG_EDITUSERNAME);
                });
            });
        });
    }
    //validate edit password data
    validateEditPassword() {
        let ok = false;
        if (this._editUser.oldPassword && this._editUser.newPassword && this._editUser.confPassword && typeof (this._editUser.isAjax) != "undefined") {
            ok = true;
        }
        return ok;
    }
    //User edits his password
    editPassword() {
        if (this.validateEditPassword()) {
            let jsonRes;
            this.editPasswordPromise().then(res => {
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica password', jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica password', err);
            });
        } //if(this.validateEditPassword()){
        else
            this._errno = EditUserController.ERR_DATAMISSED;
    }
    editPasswordPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                const data = {
                    pwd: '1',
                    oPwd: this._editUser.oldPassword,
                    nPwd: this._editUser.newPassword,
                    confPwd: this._editUser.confPassword
                };
                const params = {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    }
                };
                const response = fetch(EditUserController.EDITPROFILE_URL, params);
                response.then(res => {
                    resolve(res.text());
                }).catch(err => {
                    console.warn(err);
                    reject(EditUserController.ERR_MSG_EDITPASSWORD);
                });
            });
        });
    }
    //validate EditPersonalData data
    validatePersonalData() {
        let ok = false;
        if (this._editUser.name && this._editUser.surname && this._editUser.address && this._editUser.number && this._editUser.city && this._editUser.zip && typeof (this._editUser.isAjax) != "undefined") {
            ok = true;
        }
        return ok;
    }
    //User edits his personal data
    editPersonalData() {
        if (this.validatePersonalData()) {
            let jsonRes;
            this.editPersonalDataPromise().then(res => {
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica dati personali', jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica dati personali', err);
            });
        } //if(this.validatePersonalData()){
        else
            EditUserController.ERR_DATAMISSED;
    }
    //Do the edit personal data action
    editPersonalDataPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                let data = {
                    pers: '1',
                    name: this._editUser.name,
                    surname: this._editUser.surname,
                    address: this._editUser.address,
                    number: this._editUser.number,
                    city: this._editUser.city,
                    zip: this._editUser.zip,
                    paypalMail: this._editUser.paypalMail,
                    clientId: this._editUser.clientId
                };
                const params = {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    }
                };
                const response = fetch(EditUserController.EDITPROFILE_URL, params);
                response.then(res => {
                    resolve(res.text());
                }).catch(err => {
                    console.warn(err);
                    reject(EditUserController.ERR_MSG_EDITPERSONALDATA);
                });
            });
        });
    }
    //Show dialog with message
    printDialog(title, message) {
        let dm, dmData, msgDialog;
        dmData = {
            title: title,
            message: message
        };
        dm = new DialogMessage(dmData);
        msgDialog = $('#' + dm.id);
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
            //User press OK button
            msgDialog.dialog('destroy');
            msgDialog.remove();
        });
    }
}
//constants
EditUserController.EDITPROFILE_URL = 'funzioni/editProfile.php';
//errors
EditUserController.ERR_NOEDITUSEROBJECT = 1;
EditUserController.ERR_DATAMISSED = 2;
EditUserController.ERR_INVALIDACTION = 3;
EditUserController.ERR_MSG_EDITPASSWORD = "Errore durante la modifica della password";
EditUserController.ERR_MSG_EDITPERSONALDATA = "Errore durante la modifica dei dati personali";
EditUserController.ERR_MSG_EDITUSERNAME = "Errore durante la modifica del nome utente";
EditUserController.ERR_MSG_NOEDITUSEROBJECT = "L'oggetto EditUser non è stato definito";
EditUserController.ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
EditUserController.ERR_MSG_INVALIDACTION = "L'operazione scelta non è valida";
