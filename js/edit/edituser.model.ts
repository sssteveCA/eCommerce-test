import EditUserInterface from "./data.interface";

//Model used to change user profile data
export default class EditUser{

    private static ACTION_USERNAME: number = 1;
    private static ACTION_PASSWORD: number = 2;
    private static ACTION_PERSONALDATA: number = 3;

    private static ERR_INVALIDACTION = "Operazione non valida";
    private static ERR_MISSINGDATAREQUIRED = "Uno o più dati richiesti non sono stati impostati";

    _action: number;
    _username: string | null = null;
    _oldPassword: string | null = null;
    _newPassword: string | null = null;
    _confPassword: string | null = null;
    _name: string | null = null;
    _surname: string | null = null;
    _address: string | null = null;
    _number: number | null = null;
    _city: string | null = null;
    _zip: string | null = null;
    _paypalMail: string | null = null;
    _clientId: string | null = null;
    _ajax: boolean;
    _error: string|null = null;

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
        if(data.ajax)this._ajax = data.ajax;
        else this._ajax = false;
    }

    get action(){return this._action;}
    get username(){return this._username;}
    get oldPassword(){return this._oldPassword;}
    get newPassword(){return this._newPassword;}
    get confPassword(){return this._confPassword;}
    get name(){return this._name;}
    get surname(){return this._surname;}
    get address(){return this._address;}
    get number(){return this._number;}
    get city(){return this._city;}
    get zip(){return this._zip;}
    get paypalMail(){return this._paypalMail;}
    get clientId(){return this._clientId;}
    get isAjax(){return this._ajax;}
    get error(){return this._error;}

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