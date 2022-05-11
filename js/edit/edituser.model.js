//Model used to change user profile data
export default class EditUser {
    constructor(data) {
        this._username = null;
        this._oldPassword = null;
        this._newPassword = null;
        this._confPassword = null;
        this._name = null;
        this._surname = null;
        this._address = null;
        this._number = null;
        this._city = null;
        this._zip = null;
        this._paypalMail = null;
        this._clientId = null;
        this._error = null;
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
        if (data.ajax)
            this._ajax = data.ajax;
        else
            this._ajax = false;
    }
    get action() { return this._action; }
    get username() { return this._username; }
    get oldPassword() { return this._oldPassword; }
    get newPassword() { return this._newPassword; }
    get confPassword() { return this._confPassword; }
    get name() { return this._name; }
    get surname() { return this._surname; }
    get address() { return this._address; }
    get number() { return this._number; }
    get city() { return this._city; }
    get zip() { return this._zip; }
    get paypalMail() { return this._paypalMail; }
    get clientId() { return this._clientId; }
    get isAjax() { return this._ajax; }
    get error() { return this._error; }
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
