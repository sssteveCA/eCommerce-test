import ResetInterface from "./reset.interface";

export default class ResetRequest{
    private _key: string;
    private _newPassword: string;
    private _confPassword: string;
    private _errno: number = 0;
    private _error: string|null = null;

    private static RESET_URL:string = "/reset";

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
            case ResetRequest.ERR_REQUEST:
                this._error = ResetRequest.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async resetPassword(): Promise<object>{
        this._errno = 0;
        let response: object = {};
        try{
            await this.resetPasswordPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = ResetRequest.ERR_REQUEST;
            response = { msg: this.error };
        }
        return response;
    }

    private async resetPasswordPromise(): Promise<string>{
        return await new Promise<string>((resolve, reject) => {
            let params: object = {
                method: 'POST',
                body: `ajax=1&chiave=${this._key}&nuova=${this._newPassword}&confNuova=${this._confPassword}`,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            const response = fetch(ResetRequest.RESET_URL,params);
            response.then(res => {
                resolve(res.text());
            }).catch(err => {
                console.warn(err);
                reject(err);
            });
        });
    }
}