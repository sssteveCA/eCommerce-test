import { Constants } from "../../constants/constants";
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
    private static ERR_FETCH_MSG:string = "Errore durante l'esecuzione dell'operazione'";

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

    public async getOrder(): Promise<string>{
        let message: string = '';
        this._errno = 0;
        try{
            await this.getOrderPromise().then(res =>{
                //console.log(res);
                let json: object = JSON.parse(res);
                //console.log(json);
                if(json[Constants.KEY_DONE] == true){
                    message = this.setOrderMessage(json['order']);
                }//if(json[Constants.KEY_DONE] == true){
                else{
                    message = json[Constants.KEY_MESSAGE];
                }
            }).catch(err => {
                throw err;
            });
        }catch(e){
            this._errno = GetOrder.ERR_FETCH;
            message = GetOrder.ERR_FETCH_MSG;
        }
        return message;
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

    private setOrderMessage(order_obj: object): string{
        let message: string = `
Dati venditore<br>     
Nome: ${order_obj['nome']}<br>
Cognome: ${order_obj['cognome']}<br>
Nato il: ${order_obj['nascita']}<br>
Residente a: ${order_obj['citta']}<br>
Indirizzo: ${order_obj['indirizzo']}, ${order_obj['numero']}<br>
CAP: ${order_obj['cap']}<br>
Indirizzo email: ${order_obj['email']}<br><br>
Dati prodotto<br>
Nome: ${order_obj['nomeP']}<br>
Categoria: ${order_obj['indirizzo']}<br>
Indirizzo: ${order_obj['tipo']}<br>
Prezzo: ${order_obj['prezzo']}€<br>
Spedizione: ${order_obj['spedizione']}€<br>
Quantità: ${order_obj['quantita']}<br>
Spedito da: ${order_obj['stato']}<br>
Totale: ${order_obj['totale']}€<br>
        `;
        return message;
    }
}