import EditUser from "./edituser.model";
import { showDialogMessage } from "../functions/functions";
import DialogMessageInterface from "../dialog/dialogmessage.interface";

export default class EditUserRequest{
    //constants
    private static EDITPROFILE_URL = '/funzioni/editProfile.php';

    //errors
    private static ERR_NOEDITUSEROBJECT = 1;
    private static ERR_DATAMISSED = 2;
    private static ERR_INVALIDACTION = 3;

    private static ERR_MSG_EDITPASSWORD = "Errore durante la modifica della password";
    private static ERR_MSG_EDITPERSONALDATA = "Errore durante la modifica dei dati personali";
    private static ERR_MSG_EDITUSERNAME = "Errore durante la modifica del nome utente";
    private static ERR_MSG_NOEDITUSEROBJECT =  "L'oggetto EditUser non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
    private static ERR_MSG_INVALIDACTION = "L'operazione scelta non è valida";

    private _editUser: EditUser;
    private _errno: number;
    private _error: string|null;

    constructor(editUser: EditUser){
        this._editUser = editUser;
        this._errno = 0;
        this._error = null;
        if(this._editUser){
            //editUser is not null
            switch(this._editUser.action){
                case EditUser.ACTION_USERNAME:
                    this.editUsername();
                    break;
                case EditUser.ACTION_PASSWORD:
                    this.editPassword();
                    break;
                case EditUser.ACTION_PERSONALDATA:
                    this.editPersonalData();
                    break;
                default:
                    this._errno = EditUserRequest.ERR_INVALIDACTION;
                    break;
            }
        }
        else this._errno = EditUserRequest.ERR_NOEDITUSEROBJECT;
    }

    get editUser(){return this._editUser;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case EditUserRequest.ERR_NOEDITUSEROBJECT:
                this._error = EditUserRequest.ERR_MSG_NOEDITUSEROBJECT;
                break;
            case EditUserRequest.ERR_DATAMISSED:
                this._error = EditUserRequest.ERR_MSG_DATAMISSED;
                break;
            case EditUserRequest.ERR_INVALIDACTION:
                this._error = EditUserRequest.ERR_MSG_INVALIDACTION;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    //validate editUsername data
    private validateEditUsername(): boolean{
        let ok = false;
        if(this._editUser.username && typeof (this._editUser.isAjax) != "undefined"){
            ok = true;
        }
        return ok;
    }

    //User edits his username
    private editUsername(): void{
        if(this.validateEditUsername()){
            let jsonRes;
            this.editUsernamePromise().then(res => {
                //console.log(res);
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica nome utente',jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica nome utente',err);
            });
        }
        else EditUserRequest.ERR_DATAMISSED;
    }

    //Do the edit Username action
    private async editUsernamePromise(): Promise<string>{
        return await new Promise((resolve,reject) => {
            const data = {
                user: '1',
                username: this._editUser.username
            };
            const params = {
                method: 'POST',
                body : JSON.stringify(data),
                headers : {
                    'Content-Type' : 'application/json',
                    Accept: 'application/json'
                }
            };
            const response = fetch(EditUserRequest.EDITPROFILE_URL,params);
            response.then(res => {
                resolve(res.text());
            }).catch(err => {
                console.warn(err);
                reject(EditUserRequest.ERR_MSG_EDITUSERNAME);
            });
        });
    }

    //validate edit password data
    private validateEditPassword(): boolean{
        let ok = false;
        if(this._editUser.oldPassword && this._editUser.newPassword && this._editUser.confPassword && typeof(this._editUser.isAjax) != "undefined"){
            ok = true;
        }
        return ok;
    }

    //User edits his password
    private editPassword(): void{
        if(this.validateEditPassword()){
            let jsonRes;
            this.editPasswordPromise().then(res => {
                //console.log(res);
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica password',jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica password',err); 
            });

        }//if(this.validateEditPassword()){
        else this._errno = EditUserRequest.ERR_DATAMISSED;
    }

    private async editPasswordPromise(): Promise<string>{
        return await new Promise((resolve,reject) => {
            const data = {
                pwd: '1',
                oPwd: this._editUser.oldPassword,
                nPwd: this._editUser.newPassword,
                confPwd: this._editUser.confPassword
            };
            const params = {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            };
            const response = fetch(EditUserRequest.EDITPROFILE_URL,params);
            response.then(res => {
                resolve(res.text());
            }).catch(err => {
                console.warn(err);
                reject(EditUserRequest.ERR_MSG_EDITPASSWORD);
            });
        });
    }

    //validate EditPersonalData data
    private validatePersonalData(): boolean{
        let ok = false;
        if(this._editUser.name && this._editUser.surname && this._editUser.address && this._editUser.number && this._editUser.city && this._editUser.zip && typeof (this._editUser.isAjax) != "undefined"){
            ok = true;
        }
        return ok;
    }

    //User edits his personal data
    private editPersonalData(): void{
        if(this.validatePersonalData()){
            let jsonRes;
            this.editPersonalDataPromise().then(res => {
                console.log(res);
                jsonRes = JSON.parse(res);
                this.printDialog('Modifica dati personali',jsonRes.msg);
            }).catch(err => {
                console.warn(err);
                this.printDialog('Modifica dati personali',err);
            });
        }//if(this.validatePersonalData()){
        else EditUserRequest.ERR_DATAMISSED;
    }

    //Do the edit personal data action
    private async editPersonalDataPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject) => {
            let data = {
                pers: '1',
                name: this._editUser.name,
                surname: this._editUser.surname,
                address: this._editUser.address,
                number: this._editUser.number,
                city: this._editUser.city,
                zip: this._editUser.zip,
                paypalMail : this._editUser.paypalMail,
                clientId: this._editUser.clientId
            };
            const params = {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            };
            const response = fetch(EditUserRequest.EDITPROFILE_URL,params);
            response.then(res => {
                resolve(res.text());
            }).catch(err => {
                console.warn(err);
                reject(EditUserRequest.ERR_MSG_EDITPERSONALDATA);
            })
        });
    }

    //Show dialog with message
    private printDialog(title: string, message: string): void{
        let dmData: DialogMessageInterface = {
            title: title,
            message: message
        };
        showDialogMessage(dmData);  

    }


}