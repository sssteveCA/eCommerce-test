//Model used to change user profile data
export default class EditUser {
    constructor(data) {
        this._error = null;
        this._action = data.action;
        if (this._action == EditUser.ACTION_USERNAME) {
            this.usernameAction(data);
        }
        else if (this._action == EditUser.ACTION_PASSWORD) {
            this.passwordAction(data);
        }
        else if (this._action == EditUser.ACTION_PERSONALDATA) {
            this.personalDataAction(data);
        }
        else
            throw EditUser.ERR_INVALIDACTION;
    }
    //User wants change his username
    usernameAction(data) {
        const usernameOk = (data.username && (data.username.trim().length > 0));
        if (usernameOk) {
            //username property exists and it's not blank
            this._username = data.username;
        }
        else
            throw EditUser.ERR_MISSINGDATAREQUIRED;
    }
    //User wants change his password
    passwordAction(data) {
        const oldPwdOk = (data.oldPassword && (data.oldPassword.trim().length > 0));
        const newPwdOk = (data.newPassword && (data.newPassword.trim().length > 0));
        const confPwdOk = (data.confPassword && (data.confPassword.trim().length > 0));
        if (oldPwdOk && newPwdOk && confPwdOk) {
            //password properties exist and are not blank
            this._oldPassword = data.oldPassword;
            this._newPassword = data.newPassword;
            this._confPassword = data.confPassword;
        }
        else
            throw EditUser.ERR_MISSINGDATAREQUIRED;
    }
    //User wants change his personal data
    personalDataAction(data) {
        const nameOk = (data.name && (data.name.trim().length > 0));
        const surnameOk = (data.surname && (data.surname.trim().length > 0));
        const addressOk = (data.address && (data.address.trim().length > 0));
        const cityOk = (data.city && (data.city.trim().length > 0));
        const zipOk = (data.zip && (data.zip.trim().length > 0));
        if (nameOk && surnameOk && addressOk && data.number && cityOk && zipOk) {
            this._name = data.name;
            this._surname = data.surname;
            this._address = data.address;
            this._number = data.number;
            this._city = data.city;
            this._zip = data.zip;
            const paypalMailOk = (data.paypalMail && (data.paypalMail.trim().length > 0));
            if (paypalMailOk)
                this._paypalMail = data.paypalMail;
            const clientIdOk = (data.clientId && (data.clientId.trim().length > 0));
            if (clientIdOk)
                this._clientId = data.clientId;
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
