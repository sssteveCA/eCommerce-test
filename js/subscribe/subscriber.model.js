//This class contains data to pass when user subscribes
export default class Subscriber {
    constructor(data) {
        this._name = data.name;
        this._surname = data.surname;
        this._birth = data.birth;
        this._sex = data.sex;
        this._address = data.address;
        this._number = data.number;
        this._city = data.city;
        this._zip = data.zip;
        this._username = data.username;
        if (data.hasOwnProperty('paypalMail'))
            this._paypalMail = data.paypalMail;
        else
            this._paypalMail = null;
        if (data.hasOwnProperty('clientId'))
            this._clientId = data.clientId;
        else
            this._clientId = null;
        this._email = data.email;
        this._password = data.password;
        if (data.hasOwnProperty('ajax'))
            this._ajax = data.ajax;
        else
            this._ajax = false;
    }
    get name() { return this._name; }
    get surname() { return this._surname; }
    get birth() { return this._birth; }
    get sex() { return this._sex; }
    get address() { return this._address; }
    get number() { return this._number; }
    get city() { return this._city; }
    get zip() { return this._zip; }
    get username() { return this._username; }
    get paypalMail() { return this._paypalMail; }
    get clientId() { return this._clientId; }
    get email() { return this._email; }
    get password() { return this._password; }
    get isAjax() { return this._ajax; }
}
