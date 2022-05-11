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
                    break;
                case EditUser.ACTION_PERSONALDATA:
                    break;
                default:
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
            let dm, dmData, msgDialog, jsonRes;
            this.editUsernamePromise().then(res => {
                jsonRes = JSON.parse(res);
                dmData = {
                    title: 'Modifica nome utente',
                    message: jsonRes.msg
                };
                dm = new DialogMessage(dmData);
                msgDialog = $('#' + dm.id);
                $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
                    //User press OK button
                    msgDialog.dialog('destroy');
                    msgDialog.remove();
                });
            }).catch(err => {
                console.warn(err);
                dmData = {
                    title: 'Modifica nome utente',
                    message: err
                };
                dm = new DialogMessage(dmData);
                msgDialog = $('#' + dm.id);
                $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
                    //User press OK button
                    msgDialog.dialog('destroy');
                    msgDialog.remove();
                });
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
}
//constants
EditUserController.EDITPROFILE_URL = 'funzioni/editProfile.php';
//errors
EditUserController.ERR_NOEDITUSEROBJECT = 1;
EditUserController.ERR_DATAMISSED = 2;
EditUserController.ERR_MSG_EDITUSERNAME = "Errore durante la modifica del nome utente";
EditUserController.ERR_MSG_NOEDITUSEROBJECT = "L'oggetto EditUser non è stato definito";
EditUserController.ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
