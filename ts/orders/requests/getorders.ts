import Order from "../models/order.model";
import GetOrdersInterface from "../interfaces/getorders.interface";
import OrderInterface from "../interfaces/order.interface";
import { Constants } from "../../constants/constants";

//Get all user orders
export default class GetOrders{

    private _operation: number; //command to sent at backend to get orders
    private _orders: Order[] = [];
    private _length: number = 0; //Number of orders retrieved
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDERS_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    public static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

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
        this._length = response['i'] as number;
        if(response[Constants.KEY_DONE] === true && this._length > 0){
            response['orders'].forEach(element => {
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
        }//if(response[Constants.KEY_DONE] === true && response['i'] > 0){

    }


}