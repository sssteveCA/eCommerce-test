import {Contact} from './contact.model';

//Do the HTTP request passing Contact object
class ContactController{

    //constants
    private static CONTACT_URL = 'funzioni/mail.php';

    //errors
    public static ERR_NOCONTACTOBJECT = 1; //Contact object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Contact object are missed

    private static ERR_MSG_NOCONTACTOBJECT = "L'oggetto Contact non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";

    _contact: Contact;
    _errno: number;
    _error: string | null;

    constructor(contact: Contact){
        this._contact = contact;
        this._errno = 0;
        this._error = null;
    }

    get contact(){return this._contact;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
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
    private validateContact() : boolean{
        let ok : boolean = false;
        if(this.contact.subject && this.contact.message && typeof(this.contact.ajax) != "undefined" ){
            ok = true;
        }//if(this.contact.subject && this.contact.message && typeof(this.contact.ajax) != "undefined" ){
        else
            this._errno = ContactController.ERR_DATAMISSED;
        return ok;
    }

    //send support email
    private sendEmail(): void {
        if(this.contact != null){
            if(this.validateContact()){ 
            }//if(this.validateContact()){
        }//if(this.contact != null){
        else
            this._errno = ContactController.ERR_NOCONTACTOBJECT;
    }

    //send support email Promise
    private async sendEmailPromise(): Promise<string>{
        return await new Promise((resolve,reject) => {
            let param = {
                method: 'POST',
                body: JSON.stringify({
                    'oggetto': this.contact.subject,
                    'messaggio': this.contact.message,
                    'ajax': this.contact.ajax
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            };
            const response = fetch(ContactController.CONTACT_URL,param);
        });
    }
}