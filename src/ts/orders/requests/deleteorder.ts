import { Constants } from "../../constants/constants";
import DeleteOrderInterface from "../interfaces/deleteorder.interface";

export default class DeleteOrder{
    private _id_order: number; //Order to be deleted
    private _operation: number; //command to sent at backend to delete the order 
    private _errno: number = 0;
    private _error: string|null = null;

    private static DELETEORDER_URL:string = '/funzioni/orderMan.php';

    //Error numbers
    private static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante la richiesta dei dati";

    constructor(data: DeleteOrderInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}

    public async deleteOrder(): Promise<object>{
        let message: object = {};
        this._errno = 0;
        try{
            await this.deleteOrderPromise().then(res =>{
                //console.log(res);
                let json: object = JSON.parse(res);
                message = {
                    done: json[Constants.KEY_DONE],
                    msg: json[Constants.KEY_MESSAGE]
                }
            }).catch(err => {
                throw err;
            })
        }catch(e){
            this._errno = DeleteOrder.ERR_FETCH;
            message = {
                done: false,
                msg: DeleteOrder.ERR_FETCH_MSG
            } 
        }
        return message;
    }

    private async deleteOrderPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject)=>{
            let body_params:string = `?idOrd=${this._id_order}&oper=${this._operation}`;
            fetch(DeleteOrder.DELETEORDER_URL+body_params).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            });
        });
    }
}