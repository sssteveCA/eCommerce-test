import Contact from './contact.model.js';
import DialogMessage from './../dialog/dialogmessage.js';
import DialogMessageInterface from '../dialog/dialogmessage.interface.js';

//Do the HTTP request passing Contact object
export default class ContactController{

    //constants
    private static CONTACT_URL = 'funzioni/mail.php';

    //errors
    public static ERR_NOCONTACTOBJECT = 1; //Contact object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Contact object are missed

    private static ERR_MSG_NOCONTACTOBJECT = "L'oggetto Contact non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
    private static ERR_MSG_MAILNOTSENT = "Errore durante l'invio della mail. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";

    _contact: Contact;
    _errno: number;
    _error: string | null;

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
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#'+dm.id);
                    dm.btOk.on('click',()=>{
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                }).catch(err => {
                    //console.warn(err);
                    dmData = {
                        title: 'Contatti',
                        message: err
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#'+dm.id);
                    dm.btOk.on('click',()=>{
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                });
            }//if(this.validateContact()){
            else
                this._errno = ContactController.ERR_DATAMISSED;
        }//if(this.contact != null){
        else
            this._errno = ContactController.ERR_NOCONTACTOBJECT;
    }

    //send support email Promise
    private async sendEmailPromise(): Promise<string>{
        return await new Promise((resolve,reject) => {
            let param = {
                method: 'POST',
                body: `oggetto=${this.contact.subject}&messaggio=${this.contact.message}&ajax=${this.contact.ajax}`,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            };
            console.log(param);
            const response = fetch(ContactController.CONTACT_URL,param);
            response.then(r => {
                resolve(r.text());
            }).catch(err => {
                console.warn(err);
                reject(ContactController.ERR_MSG_MAILNOTSENT);
            });
        });
    }
}