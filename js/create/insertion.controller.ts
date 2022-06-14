import Insertion from "./insertion.model";
import DialogMessage from "../dialog/dialogmessage";

//Create the Insertion in DB passing the Insertion object
export default class InsertionController{

    //constants
    private static INSERTION_URL = 'funzioni/upload.php';

    //errors
    private static ERR_INVALIDDATA = 1; //Data from form are invalid
    private static ERR_NOINSERTIONOBJECT = 2; //Insertion object is null

    private static ERR_MSG_INVALIDDATA = "Uno o più valori inseriti non sono validi";
    private static ERR_MSG_NOINSERTIONOBJECT = "L'oggetto Insertion è uguale a null";

    private static ERR_MSG_INSERTIONEERROR = "Errore durante l' inserimento dell'inserzione. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";

    //properties
    private _insertion: Insertion;
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(insertion: Insertion){
        this._insertion = insertion;
    }


    get insertion(){return this._insertion;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case InsertionController.ERR_INVALIDDATA:
                this._error = InsertionController.ERR_MSG_INVALIDDATA;
                break;
            case InsertionController.ERR_NOINSERTIONOBJECT:
                this._error = InsertionController.ERR_MSG_NOINSERTIONOBJECT;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    private async createPromise(): Promise<any>{
        return await new Promise((resolve,reject) => {
            const headers = {
                'Content-Type': 'application/json',
                Accept: 'application/json'
            };
            const request = fetch(InsertionController.INSERTION_URL,{
                method: 'POST',
                body: JSON.stringify(this._insertion),
                headers: headers
            });
            request.then(r => {
                resolve(r.text());
            }).catch(err => {
                reject(InsertionController.ERR_MSG_INSERTIONEERROR);
            });
        });
    }

    //HTTP request for insert the insertion in MySql DB
    private createInsertion(): void{
        this._errno = 0;
        if(this._insertion != null){
            //Insertion object is istantiated
            if(this.validateInsertion()){
                //All data are valid
                this.createPromise().then(res => {
                    console.log(res);
                }).catch(err => {
                    console.warn(err);
                });
            }//if(this.validateInsertion()){
            else
                this._errno = InsertionController.ERR_INVALIDDATA;
        }//if(this._insertion != null){
        else
            this._errno = InsertionController.ERR_NOINSERTIONOBJECT;
    }

    //Check if Insertion model has required properties
    private validateInsertion(): boolean{
        let ok = false;
        let okName: boolean = (this._insertion.name != '');
        let okImage: boolean = (this._insertion.image != null && this._insertion.image.size > 0);
        let okType: boolean = (this._insertion.type != '');
        let okPrice: boolean = (this._insertion.price > 0);
        let okShipping: boolean = (this._insertion.shipping > 0);
        let okCondition: boolean = (this._insertion.condition != '');
        let okState: boolean = (this._insertion.state != '');
        let okCity: boolean = (this._insertion.city != '');
        let okDescription: boolean = (this._insertion.description != '');
        if(okName && okImage && okType && okPrice && okShipping && okCondition && okState && okCity && okDescription){
            ok = true;
        }
        return ok;
    }


}