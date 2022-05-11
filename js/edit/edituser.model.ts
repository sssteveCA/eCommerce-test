import EditUserInterface from "./data.interface";

//Model used to change user profile data
export default class EditUser{

    private static ACTION_USERNAME: number = 1;
    private static ACTION_PASSWORD: number = 2;
    private static ACTION_PERSONALDATA: number = 3;

    private static ERR_INVALIDACTION = "Operazione non valida";
    private static ERR_MISSINGDATAREQUIRED = "Uno o più dati richiesti non sono stati impostati";

    _action: number;
    _username: string;
    _oldPassword: string;
    _newPassword: string;
    _confPassword: string;
    _name: string;
    _surname: string;
    _address: string;
    _number: number;
    _city: string;
    _zip: string;
    _paypalMail: string;
    _clientId: string;
    _ajax: boolean;
    _error: string|null;

    constructor(data: EditUserInterface){
        this._error = null;
        this._action = data.action;
        if(this._action == EditUser.ACTION_USERNAME){
            this.usernameAction(data);
        }
        else if(this._action == EditUser.ACTION_PASSWORD){
            this.passwordAction(data);
        }
        else if(this._action == EditUser.ACTION_PERSONALDATA){
            this.personalDataAction(data);
        }
        else throw EditUser.ERR_INVALIDACTION;
    }

    //User wants change his username
    private usernameAction(data: EditUserInterface): void{
        const usernameOk = (data.username && (data.username.trim().length > 0));
        if(usernameOk){
            //username property exists and it's not blank
            this._username = data.username as string;
        }
        else throw EditUser.ERR_MISSINGDATAREQUIRED;
    }

    //User wants change his password
    private passwordAction(data: EditUserInterface): void{
        const oldPwdOk = (data.oldPassword && (data.oldPassword.trim().length > 0));
        const newPwdOk = (data.newPassword && (data.newPassword.trim().length > 0));
        const confPwdOk = (data.confPassword && (data.confPassword.trim().length > 0));
        if(oldPwdOk && newPwdOk && confPwdOk){
            //password properties exist and are not blank
            this._oldPassword = data.oldPassword as string;
            this._newPassword = data.newPassword as string;
            this._confPassword = data.confPassword as string;
        }
        else throw EditUser.ERR_MISSINGDATAREQUIRED;
    }

    //User wants change his personal data
    private personalDataAction(data: EditUserInterface): void{
        const nameOk = (data.name && (data.name.trim().length > 0));
        const surnameOk = (data.surname && (data.surname.trim().length > 0));
        const addressOk = (data.address && (data.address.trim().length > 0));
        const cityOk = (data.city && (data.city.trim().length > 0));
        const zipOk = (data.zip && (data.zip.trim().length > 0));
        if(nameOk && surnameOk && addressOk && data.number && cityOk && zipOk){
            this._name = data.name as string;
            this._surname = data.surname as string;
            this._address = data.address as string;
            this._number = data.number as number;
            this._city = data.city as string;
            this._zip = data.zip as string;
            const paypalMailOk = (data.paypalMail && (data.paypalMail.trim().length > 0));
            if(paypalMailOk)this._paypalMail = data.paypalMail as string;
            const clientIdOk = (data.clientId && (data.clientId.trim().length > 0));
            if(clientIdOk)this._clientId = data.clientId as string;
        }
        else throw EditUser.ERR_MISSINGDATAREQUIRED;
    }
}