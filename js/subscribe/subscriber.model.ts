import SubsciberInterface from "./data.interface";

//This class contains data to pass when user subscribes
export default class Subscriber{
    private _name: string;
    private _surname: string;
    private _birth: Date|string; //Subscriber birth date
    private _sex: string; //Male or female
    private _address: string; //Subscriber living street
    private _number: number; //House number of subscriber
    private _city: string; //Subscriber living city
    private _zip: string; //Subscriber ZIP code
    private _username: string;
    //Email of paypal account needed if subscriber sells products
    private _paypalMail: string|null;  
    private _clientId: string|null; //Unique ID of Paypal user
    private _email: string; //Personal email
    private _password: string;
    private _confPass: string;
    private _ajax: boolean; //True if Subscriber data are passed via AJAX request

    constructor(data: SubsciberInterface){
        this._name = data.name;
        this._surname = data.surname;
        this._birth = data.birth;
        this._sex = data.sex;
        this._address = data.address;
        this._number = data.number;
        this._city = data.city;
        this._zip = data.zip;
        this._username = data.username;
        if(data.hasOwnProperty('paypalMail'))this._paypalMail = data.paypalMail as string;
        else this._paypalMail = null;
        if(data.hasOwnProperty('clientId'))this._clientId = data.clientId as string;
        else this._clientId = null;
        this._email = data.email;
        this._password = data.password;
        this._confPass = data.confPass;
        if(data.hasOwnProperty('ajax'))this._ajax = data.ajax as boolean;
        else this._ajax = false;
    }

    get name(){return this._name;}
    get surname(){return this._surname;}
    get birth(){return this._birth;}
    get sex(){return this._sex;}
    get address(){return this._address;}
    get number(){return this._number;}
    get city(){return this._city;}
    get zip(){return this._zip;}
    get username(){return this._username;}
    get paypalMail(){return this._paypalMail;}
    get clientId(){return this._clientId;}
    get email(){return this._email;}
    get password(){return this._password;}
    get confPass(){return this._confPass;}
    get isAjax(){return this._ajax;}

}