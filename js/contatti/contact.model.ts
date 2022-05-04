import {ContactInterface} from './data.interface';

//Data passed in HTTP request
export class Contact{
    _subject: string;
    _message: string;
    _ajax: boolean; //if this object is passed with ajax request

    constructor(data: ContactInterface){
        this._subject = data.subject;
        this._message = data.message;
        if(data.hasOwnProperty('ajax'))
            this._ajax = data.ajax as boolean;
        else
            this._ajax = false;
    }

    get subject(){return this._subject;}
    get message(){return this._message;}
    get ajax(){return this._ajax;}

    set subject(subject: string){this._subject = subject;}
    set message(message: string){this._message = message;}

}