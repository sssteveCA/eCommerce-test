import ConfirmInterface from "./confirm.interface";

export default class ConfirmRequest{
    private _oper: number;
    private _ido: number;
    private _idp: number;
    private _errno: number = 0;
    private _error: string|null = null;

    private static CONFIRM_URL: string = "";

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
            default:
                this._error = null;
                break;
        }
        return this._error;
    }


}