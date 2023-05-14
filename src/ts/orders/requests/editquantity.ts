import { Constants } from "../../constants/constants";
import EditQuantityInterface from "../interfaces/editquantity.interface";

export default class EditQuantity{
    private _id_order: number; //Order to be obtained
    private _operation: number; //command to sent at backend to edit order quantity
    private _quantity: number; //new order quantity
    private _errno: number = 0;
    private _error: string|null = null;

    private static EDITQUANTITY_URL:string = '/funzioni/orderMan.php';

    //Error numbers
    public static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante l'esecuzione dell'operazione'";

    constructor(data: EditQuantityInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
        this._quantity = data.quantity;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
    get quantity(){return this._quantity;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case EditQuantity.ERR_FETCH:
                this._error = EditQuantity.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async editQuantity(): Promise<object>{
        let message: object = {};
        this._errno = 0;
        try{
            await this.editQuantityPromise().then(res => {
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
            this._errno = EditQuantity.ERR_FETCH;
            message = {
               done: false,
               msg: EditQuantity.ERR_FETCH_MSG 
            };
        }
        return message;
    }

    public async editQuantityPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject) => {
            let body_params: string = `?idOrd=${this._id_order}&oper=${this._operation}&quantita=${this._quantity}`;
            fetch(EditQuantity.EDITQUANTITY_URL+body_params).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }
}