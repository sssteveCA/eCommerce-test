import DeleteProductInterface from "../interfaces/deleteproduct.interface";

export default class DeleteProduct{
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
            case DeleteProduct.ERR_REQUEST:
                this._error = DeleteProduct.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async deleteProduct(): Promise<object>{
        this._errno = 0;
        let response: object = {};
        try{
            await this.deleteProductPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                console.warn(err);
                throw err;
            });
        }catch(e){
            this._errno = DeleteProduct.ERR_REQUEST;
            response = {done: false, msg: this.error };
        }
        return response;
    }

    private async deleteProductPromise(): Promise<string>{
        return await new Promise<string>((resolve, reject)=>{
            fetch(DeleteProduct.DELETE_PRODUCT_URL,{
                method: 'POST',
                headers: {
                    'Accept': 'application/json', 'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ajax: 1, idp: this._productId
                })
            }).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            })
        });
    }

}