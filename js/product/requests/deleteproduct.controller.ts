import DeleteProductInterface from "../interfaces/deleteproduct.interface";

export default class DeleteProductController{
    private _productId: string;
    private _errno: number = 0;
    private _error: string|null = null;

    private static DELETE_PRODUCT_URL: string = "funzioni/elimina.php";

    public static ERR_REQUEST:number = 1;

    private static ERR_REQUEST_MSG: string = "Errore durante la cancellazione del prodotto";

    constructor(data: DeleteProductInterface){
        this._productId = data.productId;
    }

    get productId(){ return this._productId; }
    get errno(){ return this._errno; }
    get error(){ 
        switch(this._errno){
            case DeleteProductController.ERR_REQUEST:
                this._error = DeleteProductController.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

}