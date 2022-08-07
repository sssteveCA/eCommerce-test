import Order from "../models/order.model";

//Get all user orders
export default class GetOrders{

    private _orders: Order[];
    private _length: number; //Number of orders retrieved
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDERS_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    private static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

    constructor(){}

    get orders(){return this._orders;}
    get length(){return this._length;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case GetOrders.ERR_FETCH:
                this._error = GetOrders.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async getOrders(): Promise<object>{
        let response: object = {};
        try{
            await this.getOrdersPromise().then(res => {
                console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = GetOrders.ERR_FETCH;
            response = {
                done: false,
                msg: GetOrders.ERR_FETCH_MSG
            };
        }
        return response;
    }

    private async getOrdersPromise(): Promise<string>{
        let response = await new Promise<string>((resolve,reject)=>{
            fetch(GetOrders.GETORDERS_URL+'?oper=0').then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
        return response;
    }


}