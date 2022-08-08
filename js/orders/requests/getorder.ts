import GetOrderInterface from "../interfaces/getorder.interface";
import Order from "../models/order.model";

export default class GetOrder{
    private _id_order: string; //Order to be obtained
    private _operation: string; //command to sent at backend to get the order
    private _order: Order;
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDER_URL:string = 'funzioni/orderMan.php'

    constructor(data: GetOrderInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async getOrder(): Promise<Order|null>{
        let order: Order|null = null;
        return order;
    }

    public async getOrderPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject)=>{
            let body_params:string = `?idOrd=${this._id_order}&oper=${this._operation}`;
            fetch(GetOrder.GETORDER_URL,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: body_params
            }).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }
}