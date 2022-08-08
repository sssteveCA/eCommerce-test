import Order from "../models/order.model";
import GetOrdersInterface from "../interfaces/getorders.interface";
import OrderInterface from "../interfaces/order.interface";

//Get all user orders
export default class GetOrders{

    private _operation: string; //command to sent at backend to get orders
    private _orders: Order[];
    private _length: number; //Number of orders retrieved
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDERS_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    private static ERR_FETCH:number = 1;
    private static ERR_NOORDERS:number = 2;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";
    private static ERR_NOORDERS_MSG:string = "Nessun ordine effettuato";

    constructor(data: GetOrdersInterface){
        this._operation = data.operation;
    }

    get operation(){return this._operation;}
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
        this._errno = 0;
        try{
            await this.getOrdersPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
                console.log(response);
                this.insertOrders(response);
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
            fetch(GetOrders.GETORDERS_URL+'?oper='+this._operation).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
        return response;
    }

    //Insert the retrieve orders in the orders property array
    private insertOrders(response: object): void{
        if(response['done'] === true){
            response['orders'].array.forEach(element => {
                let payed: boolean = false;
                let cart: boolean = false;
                if(element.hasOwnProperty('pagato')){
                    if(element['pagato'] == '1')
                        payed = true;
                }
                if(element.hasOwnProperty('carrello')){
                    if(element['carrello'] == '1')
                        cart = true;
                }
                let o_data: OrderInterface = {
                    id: element.id as number,
                    idc: element.idc as number,
                    idp: element.idp as number,
                    idv: element.idv as number,
                    date: element.data,
                    quantity: element.quantita as number,
                    total: element.totale as number,
                    payed: payed,
                    cart: cart
                };
                let order: Order = new Order(o_data);
                this._orders.push(order);
            });
        }
        else{
            this._errno = GetOrders.ERR_NOORDERS;
        }

    }


}