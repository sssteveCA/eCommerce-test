import ConfirmInterface from "./confirm.interface";

export default class ConfirmRequest{
    private _oper: number;
    private _ido: number;
    private _idp: number;
    private _errno: number = 0;
    private _error: string|null = null;

    public static ERR_CONFIRM: number = 1;

    private static ERR_CONFIRM_MSG: string = "Errore durante l'esecuzione della richiesta";

    private static CONFIRM_URL: string = "./funzioni/cartMan.php";

    constructor(data: ConfirmInterface){
        this._oper = data.oper;
        this._ido = data.ido;
        this._idp = data.idp;
    }

    get ido(){return this._ido;}
    get idp(){return this._idp;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case ConfirmRequest.ERR_CONFIRM:
                this._error = ConfirmRequest.ERR_CONFIRM_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async confirmRequest(): Promise<object>{
        let response: object = {};
        try{
            await this.confirmRequestPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = ConfirmRequest.ERR_CONFIRM;
            response = {done: false, msg: this.error};
        }
        return response;
    }

    private async confirmRequestPromise(): Promise<string>{
        return await new Promise((resolve,reject)=>{
            fetch(ConfirmRequest.CONFIRM_URL,{
                method: 'POST',
                body: JSON.stringify({
                   ajax: 1, oper: this._oper, ido: this._ido, idp: this._idp
                }),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }


}