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

    constructor(data: DeleteCartOrderInterface){
        this._operation = data.operation;
        this._order_id = data.order_id;
    }

    get operation(){return this._operation;}
    get order_id(){return this._order_id;}
}