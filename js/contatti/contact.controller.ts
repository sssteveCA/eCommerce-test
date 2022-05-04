import {Contact} from './contact.model';

//Do the HTTP request passing Contact object
class ContactController{

    public static ERR_NOCONTACTOBJECT = 1; //Contact object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Contact object are missed

    public static ERR_MSG_NOCONTACTOBJECT = "L'oggetto Contact non è stato definito";
    public static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";

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
        if(this.contact._subject && this.contact._message){
            ok = true;
        }//if(this.contact._subject && this.contact._message){
        else
            this._errno = ContactController.ERR_DATAMISSED;
        return ok;
    }

    //send support email
    private sendEmail(): void{
        if(this.contact != null){

        }//if(this.contact != null){
        else
            this._errno = ContactController.ERR_NOCONTACTOBJECT;
    }
}