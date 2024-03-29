import Contact from './contact.model';
import DialogMessage from '../dialog/dialogmessage';
import DialogMessageInterface from '../dialog/dialogmessage.interface';
import { showDialogMessage } from '../functions/functions';

//Do the HTTP request passing Contact object
export default class ContactRequest{

    //constants
    private static CONTACT_URL = '/contacts';

    //errors
    public static ERR_NOCONTACTOBJECT = 1; //Contact object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Contact object are missed

    private static ERR_MSG_NOCONTACTOBJECT = "L'oggetto Contact non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
    private static ERR_MSG_MAILNOTSENT = "Errore durante l'invio della mail. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";

    private _contact: Contact;
    private _errno: number;
    private _error: string | null;

    constructor(contact: Contact){
        this._contact = contact;
        this._errno = 0;
        this._error = null;
        this.sendEmail();
    }

    get contact(){return this._contact;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case ContactRequest.ERR_NOCONTACTOBJECT:
                this._error = ContactRequest.ERR_MSG_NOCONTACTOBJECT;
                break;
            case ContactRequest.ERR_DATAMISSED:
                this._error = ContactRequest.ERR_MSG_DATAMISSED;
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
        return ok;
    }

    //send support email
    private sendEmail(): void {
        if(this.contact != null){
            if(this.validateContact()){ 
                let spinner: JQuery<HTMLDivElement> = $('#contacts-spinner');
                let dm: DialogMessage,dmData: DialogMessageInterface,msgDialog: JQuery<HTMLElement>;
                spinner.toggleClass("invisible");
                this.sendEmailPromise().then(res => {
                    spinner.toggleClass("invisible");
                    //console.log(res);
                    let jsonRes = JSON.parse(res);
                    dmData = {
                        title: 'Contatti',
                        message: jsonRes.msg
                    };
                    showDialogMessage(dmData);
                }).catch(err => {
                    //console.warn(err);
                    dmData = {
                        title: 'Contatti',
                        message: err
                    };
                    dm = new DialogMessage(dmData);
                    showDialogMessage(dmData);
                });
            }//if(this.validateContact()){
            else
                this._errno = ContactRequest.ERR_DATAMISSED;
        }//if(this.contact != null){
        else
            this._errno = ContactRequest.ERR_NOCONTACTOBJECT;
    }

    //send support email Promise
    private async sendEmailPromise(): Promise<string>{
        let body: string = `oggetto=${this._contact.subject}&messaggio=${this._contact.message}&ajax=${this._contact.ajax}`;
        if(this._contact.email) body = `email=${this._contact.email}&${body}`;
        return await new Promise((resolve,reject) => {
            let param = {
                method: 'POST',
                body: body,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            };
            //console.log(param);
            const response = fetch(ContactRequest.CONTACT_URL,param);
            response.then(r => {
                resolve(r.text());
            }).catch(err => {
                console.warn(err);
                reject(ContactRequest.ERR_MSG_MAILNOTSENT);
            });
        });
    }
}