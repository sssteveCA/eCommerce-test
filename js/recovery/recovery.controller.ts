import RecoveryInterface from "./recovery.interface";

export default class RecoveryController{

    private _email: string;
    private _errno: number = 0;
    private _error: string|null = null;

    private static RECOVERY_URL: string = "funzioni/mail.php";

    public static ERR_REQUEST:number = 1;

    private static ERR_REQUEST_MSG: string = "Errore durante l'invio della mail";

    constructor(data: RecoveryInterface){
        this._email = data.email;
    }

    get email(){ return this._email; }
    get errno(){ return this._errno; }
    get error(){ 
        switch(this._errno){
            case RecoveryController.ERR_REQUEST:
                this._error = RecoveryController.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

}