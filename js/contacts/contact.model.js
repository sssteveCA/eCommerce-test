//Data passed in HTTP request
export default class Contact {
    constructor(data) {
        this._subject = data.subject;
        this._message = data.message;
        if (data.hasOwnProperty('ajax'))
            this._ajax = data.ajax;
        else
            this._ajax = false;
    }
    get subject() { return this._subject; }
    get message() { return this._message; }
    get ajax() { return this._ajax; }
    set subject(subject) { this._subject = subject; }
    set message(message) { this._message = message; }
}
