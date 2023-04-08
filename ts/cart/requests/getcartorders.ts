import { Constants } from "../../constants/constants";
import CartOrderInterface from "../interfaces/cartorder.interface";
import GetCartOrdersInterface from "../interfaces/getcartorders.interface";
import CartOrder from "../models/cartorder.model";

export default class GetCartOrders{
    private _operation: number; //command to sent at backend to get orders in backend
    private _cart_orders: CartOrder[] = [];
    private _length: number = 0; //Number of orders in cart retrieved
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETCARTORDERS_URL:string = 'funzioni/cartMan.php';

    //Error numbers
    public static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

    constructor(data: GetCartOrdersInterface){
        this._operation = data.operation;
    }

    get operation(){return this._operation;}
    get cart_orders(){return this._cart_orders;}
    get length(){return this._length;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case GetCartOrders.ERR_FETCH:
                this._error = GetCartOrders.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async getCartOrders(): Promise<object>{
        let response: object = {};
        this._errno = 0;
        try{
            await this.getCartOrdersPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
                //console.log(response);
                //this.insertCartOrders(response);
            }).catch(err => {
                throw err;
            });
        }catch(e){
            console.warn(e);
            this._errno = GetCartOrders.ERR_FETCH;
            response = {
                done: false,
                msg: GetCartOrders.ERR_FETCH_MSG
            };
        }
        return response;
    }

    private async getCartOrdersPromise(): Promise<string>{
        let response = await new Promise<string>((resolve, reject) => {
            let post_data: object = {
                'ajax': '1',
                'oper': '1'
            };
            fetch(GetCartOrders.GETCARTORDERS_URL,{
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(post_data)
            }).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
        return response;
    }

    //Insert the retrieve cart orders in the orders property array
    private insertCartOrders(response: object): void{
        if(response[Constants.KEY_DONE] === true){
            response['carrello'].forEach(item => {
                let co_data: CartOrderInterface = {
                    ido: item.ido,
                    idp: item.idp,
                    idv: item.idv,
                    name: item.nome,
                    image: item.image,
                    type: item.tipo,
                    product_price: item.prezzo,
                    quantity: item.quantita,
                    shipping: item.spedizione,
                    total: item.total
                };
                let cartOrder = new CartOrder(co_data);
                this._cart_orders.push(cartOrder);
            });//response['carrello'].forEach(item => {
        }//if(response[Constants.KEY_DONE] === true){
        this._length = response['n_orders'] as number;
        
    }

}