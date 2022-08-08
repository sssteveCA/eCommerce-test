import EditQuantityInterface from "../interfaces/editquantity.interface";

export default class EditQuantity{
    private _id_order: string; //Order to be obtained
    private _operation: string; //command to sent at backend to edit order quantity
    private _errno: number = 0;
    private _error: string|null = null;

    private static GETORDERS_URL:string = 'funzioni/orderMan.php';

    //Error numbers
    public static ERR_FETCH:number = 1;

    //Error messages
    private static ERR_FETCH_MSG:string = "Errore durante l'esecuzione dell'operazione'";

    constructor(data: EditQuantityInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
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

    public async editQuantity(): Promise<string>{
        let message: string = '';
        this._errno = 0;
        try{

        }catch(e){
            this._errno = EditQuantity.ERR_FETCH;
            message = EditQuantity.ERR_FETCH_MSG;
        }
        return message;
    }
}