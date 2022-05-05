var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
//Do the HTTP request passing Contact object
export default class ContactController {
    constructor(contact) {
        this._contact = contact;
        this._errno = 0;
        this._error = null;
        this.sendEmail();
    }
    get contact() { return this._contact; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case ContactController.ERR_NOCONTACTOBJECT:
                this._error = ContactController.ERR_MSG_NOCONTACTOBJECT;
                break;
            case ContactController.ERR_DATAMISSED:
                this._error = ContactController.ERR_MSG_DATAMISSED;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    //check if Contact object has the required properties
    validateContact() {
        let ok = false;
        if (this.contact.subject && this.contact.message && typeof (this.contact.ajax) != "undefined") {
            ok = true;
        } //if(this.contact.subject && this.contact.message && typeof(this.contact.ajax) != "undefined" ){
        return ok;
    }
    //send support email
    sendEmail() {
        if (this.contact != null) {
            if (this.validateContact()) {
                this.sendEmailPromise().then(msg => {
                    console.log(msg);
                }).catch(err => {
                    console.warn(err);
                });
            } //if(this.validateContact()){
            else
                this._errno = ContactController.ERR_DATAMISSED;
        } //if(this.contact != null){
        else
            this._errno = ContactController.ERR_NOCONTACTOBJECT;
    }
    //send support email Promise
    sendEmailPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                let param = {
                    method: 'POST',
                    body: `oggetto=${this.contact.subject}&messaggio=${this.contact.message}&ajax=${this.contact.ajax}`,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                };
                console.log(param);
                const response = fetch(ContactController.CONTACT_URL, param);
                response.then(r => {
                    resolve(r.text());
                }).catch(err => {
                    console.warn(err);
                    reject(ContactController.ERR_MSG_MAILNOTSENT);
                });
            });
        });
    }
}
//constants
ContactController.CONTACT_URL = 'funzioni/mail.php';
//errors
ContactController.ERR_NOCONTACTOBJECT = 1; //Contact object is null
ContactController.ERR_DATAMISSED = 2; //One or more properties of Contact object are missed
ContactController.ERR_MSG_NOCONTACTOBJECT = "L'oggetto Contact non è stato definito";
ContactController.ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
ContactController.ERR_MSG_MAILNOTSENT = "Errore durante l'invio della mail. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";
