import EditUser from "./edituser.model.js";
import DialogMessage from "../dialog/dialogmessage.js";

export default class EditUserController{
    //constants
    private static EDITPROFILE_URL = 'funzioni/editProfile.php';

    //errors
    private static ERR_NOEDITUSEROBJECT = 1;
    private static ERR_DATAMISSED = 2;

    private static ERR_MSG_EDITUSERNAME = "Errore durante la modifica del nome utente";
    private static ERR_MSG_NOEDITUSEROBJECT =  "L'oggetto EditUser non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";



    _editUser: EditUser;
    _errno: number;
    _error: string|null;

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
                    break;
                case EditUser.ACTION_PERSONALDATA:
                    break;
                default:
                    break;
            }
        }
        else this._errno = EditUserController.ERR_NOEDITUSEROBJECT;
    }

    get editUser(){return this._editUser;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case EditUserController.ERR_NOEDITUSEROBJECT:
                this._error = EditUserController.ERR_MSG_NOEDITUSEROBJECT;
                break;
            case EditUserController.ERR_DATAMISSED:
                this._error = EditUserController.ERR_MSG_DATAMISSED;
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
            let dm, dmData,msgDialog: JQuery<HTMLElement>, jsonRes;
            this.editUsernamePromise().then(res => {
                jsonRes = JSON.parse(res);
                dmData = {
                    title: 'Modifica nome utente',
                    message: jsonRes.msg
                };
                dm = new DialogMessage(dmData);
                msgDialog = $('#'+dm.id);
                $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click',()=>{
                    //User press OK button
                    msgDialog.dialog('destroy');
                    msgDialog.remove();
                });
            }).catch(err => {
                console.warn(err);
                dmData = {
                    title: 'Modifica nome utente',
                    message: err
                };
                dm = new DialogMessage(dmData);
                msgDialog = $('#'+dm.id);
                $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click',()=>{
                    //User press OK button
                    msgDialog.dialog('destroy');
                    msgDialog.remove();
                });
            });
        }
        else EditUserController.ERR_DATAMISSED;
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
            const response = fetch(EditUserController.EDITPROFILE_URL,params);
            response.then(res => {
                resolve(res.text());
            }).catch(err => {
                console.warn(err);
                reject(EditUserController.ERR_MSG_EDITUSERNAME);
            });
        });
    }


}