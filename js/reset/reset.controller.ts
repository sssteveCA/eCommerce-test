import ResetInterface from "./reset.interface";

export default class ResetController{
    private _key: string;
    private _newPassword: string;
    private _confPassword: string;
    private _errno: number = 0;
    private _error: string|null = null;

    public static ERR_REQUEST:number = 1;

    private static ERR_REQUEST_MSG: string = "Si è verificato un errore durante la reimpostazione della passord";

    constructor(data: ResetInterface){
        this._key = data.key;
        this._newPassword = data.newPassword;
        this._confPassword = data.confPassword;
    }

    get key(){ return this._key; }
    get newPassword(){ return this._newPassword; }
    get confPassword(){ return this._confPassword; }
    get error(){ 
        switch(this._errno){
            case ResetController.ERR_REQUEST:
                this._error = ResetController.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}