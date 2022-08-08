import GetOrderInterface from "../interfaces/getorder.interface";
import Order from "../models/order.model";

export default class GetOrder{
    private _id_order: number; //Order to be obtained
    private _operation: number; //command to sent at backend to get the order
    private _order: Order;
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDER_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    private static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

    constructor(data: GetOrderInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case GetOrder.ERR_FETCH:
                this._error = GetOrder.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async getOrder(): Promise<Order|null>{
        let order: Order|null = null;
        this._errno = 0;
        try{
            await this.getOrderPromise().then(res =>{
                console.log(res);
            }).catch(err => {
                throw err;
            })

        }catch(e){
            this._errno = GetOrder.ERR_FETCH;
        }
        return order;
    }

    private async getOrderPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject)=>{
            let body_params:string = `?idOrd=${this._id_order}&oper=${this._operation}`;
            fetch(GetOrder.GETORDER_URL+body_params).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }
}