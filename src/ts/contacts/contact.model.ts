import { Constants } from '../constants/constants';
import ContactInterface from './contact.interface';

//Data passed in HTTP request
export default class Contact{
    private _email: string;
    private _subject: string;
    private _message: string;
    private _ajax: boolean; //if this object is passed with ajax request

    constructor(data: ContactInterface){
        if(data.email) this._email;
        this._subject = data.subject;
        this._message = data.message;
        if(data.ajax) this._ajax = data.ajax as boolean;
        else this._ajax = false;
    }

    get email(){return this._email;}
    get subject(){return this._subject;}
    get message(){return this._message;}
    get ajax(){return this._ajax;}

    set email(email: string){this._email = email;}
    set subject(subject: string){this._subject = subject;}
    set message(message: string){this._message = message;}

}