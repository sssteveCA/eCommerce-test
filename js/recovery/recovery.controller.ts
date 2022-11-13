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

    public async sendRecoveryMail(): Promise<object>{
        this._errno = 0;
        let response: object = {};
        try{
            await this.sendRecoveryMailPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = RecoveryController.ERR_REQUEST;
            response = { msg: this.error };
        }
        return response;
    }

    private async sendRecoveryMailPromise(): Promise<string>{
        return await new Promise<string>((resolve, reject) => {
            let params: object = {
                method: 'POST',
                body: `ajax=1&email=${this._email}`,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            };
            const response = fetch(RecoveryController.RECOVERY_URL,params);
            response.then(r => {
                resolve(r.text());
            }).catch(err => {
                console.warn(err);
                reject(err);
            });
        });
    }
}