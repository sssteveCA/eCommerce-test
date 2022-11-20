import ProductMailInterface from "../interfaces/productmail.interface";

export default class ProductMailController{
    private _email: string;
    private _subject: string;
    private _message: string;
    private _errno: number = 0;
    private _error: string|null = null;

    private static RECOVERY_URL: string = "funzioni/mail.php";

    public static ERR_REQUEST:number = 1;

    private static ERR_REQUEST_MSG: string = "Errore durante l'invio della mail";

    constructor(data: ProductMailInterface){
        this._email = data.email;
        this._subject = data.subject;
        this._message = data.message;
    }

    get email(){ return this._email; }
    get subject(){ return this._subject; }
    get message(){ return this._message; }
    get errno(){ return this._errno; }
    get error(){ 
        switch(this._errno){
            case ProductMailController.ERR_REQUEST:
                this._error = ProductMailController.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}