import { Constants } from "../../constants/constants";
import DeleteCartOrderInterface from "../interfaces/deletecartorder.interface";

export default class DeleteCartOrder{
    private _operation: number; //command to sent at backend to get orders in backend
    private _order_id: number; //Order id to remove from cart

    private _errno: number = 0;
    private _error: string|null = null;

    //Error numbers
    public static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante l'esecuzione dell'operazione";

    private static DELETCARTORDER_URL:string = '/funzioni/cartMan.php';

    constructor(data: DeleteCartOrderInterface){
        this._operation = data.operation;
        this._order_id = data.order_id;
    }

    get operation(){return this._operation;}
    get order_id(){return this._order_id;}

    public async deleteCartOrder(): Promise<object>{
        let message: object = {};
        this._errno = 0;
        try{
            await this.deleteCartOrderPromise().then(res =>{
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
            this._errno = DeleteCartOrder.ERR_FETCH;
            message = {
                done: false,
                msg: DeleteCartOrder.ERR_FETCH_MSG
            };
        }
        return message;
    }

    private async deleteCartOrderPromise(): Promise<string>{
        return await new Promise<string>((resolve,reject)=>{
            let post_data: object = {
                ajax: 1,
                oper: 3,
                ido: this._order_id
            };
            fetch(DeleteCartOrder.DELETCARTORDER_URL,{
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
    }
}