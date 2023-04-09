import { Constants } from "../../constants/constants";
import AddToCartInterface from "../interfaces/addtocart.interface";

export default class AddToCart{
    private _id_order: number; //Order to be added to cart
    private _operation: number; //command to sent at backend to add order to cart
    private _errno: number = 0;
    private _error: string|null = null;
   
    private static ADDTOCART_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    private static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

    constructor(data: AddToCartInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case AddToCart.ERR_FETCH:
                this._error = AddToCart.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async AddToCart(): Promise<object>{
        let message: object = {};
        this._errno = 0;
        try{
            await this.AddToCartPromise().then(res =>{
                //console.log(res);
                let json:object = JSON.parse(res);
                message = {
                    done: json[Constants.KEY_DONE],
                    msg: json[Constants.KEY_MESSAGE]
                };
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = AddToCart.ERR_FETCH;
            message = {
                done: false,
                msg: AddToCart.ERR_FETCH_MSG
            };
        }
        return message;
    }

    private async AddToCartPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject)=>{
            let body_params:string = `?idOrd=${this._id_order}&oper=${this._operation}`;
            fetch(AddToCart.ADDTOCART_URL+body_params).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }
}