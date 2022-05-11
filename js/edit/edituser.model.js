//Model used to change user profile data
export default class EditUser {
    constructor(data) {
        this._error = null;
        this._action = data.action;
        if (this._action == EditUser.ACTION_USERNAME) {
            this.usernameAction(data);
        }
        else if (this._action == EditUser.ACTION_PASSWORD) {
        }
        else if (this._action == EditUser.ACTION_PERSONALDATA) {
        }
        else
            throw EditUser.ERR_INVALIDACTION;
    }
    //User wants change his username
    usernameAction(data) {
        let usernameOk = (data.username && (data.username.trim().length > 0));
        if (usernameOk) {
            //username property exists and it's not blank
            this._username = data.username;
        }
        else
            throw EditUser.ERR_MISSINGDATAREQUIRED;
    }
    //User wants change his password
    passwordAction(data) {
        let oldPwdOk = (data.oldPassword && (data.oldPassword.trim().length > 0));
        let newPwdOk = (data.newPassword && (data.newPassword.trim().length > 0));
        let confPwdOk = (data.confPassword && (data.confPassword.trim().length > 0));
        if (oldPwdOk && newPwdOk && confPwdOk) {
            //password properties exist and are not blank
            this._oldPassword = data.oldPassword;
            this._newPassword = data.newPassword;
            this._confPassword = data.confPassword;
        }
        else
            throw EditUser.ERR_MISSINGDATAREQUIRED;
    }
}
EditUser.ACTION_USERNAME = 1;
EditUser.ACTION_PASSWORD = 2;
EditUser.ACTION_PERSONALDATA = 3;
EditUser.ERR_INVALIDACTION = "Operazione non valida";
EditUser.ERR_MISSINGDATAREQUIRED = "Uno o più dati richiesti non sono stati impostati";
