import Insertion from "./insertion.model";
import DialogMessage from "../dialog/dialogmessage";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import { Constants } from "../constants/constants";

//Create the Insertion in DB passing the Insertion object
export default class InsertionRequest{

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
        this.createInsertion();
    }


    get insertion(){return this._insertion;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            case InsertionRequest.ERR_INVALIDDATA:
                this._error = InsertionRequest.ERR_MSG_INVALIDDATA;
                break;
            case InsertionRequest.ERR_NOINSERTIONOBJECT:
                this._error = InsertionRequest.ERR_MSG_NOINSERTIONOBJECT;
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
            let fd = new FormData();
            fd.append('idU',this._insertion.idU.toString());
            fd.append('name',this._insertion.name);
            fd.append('image',this._insertion.image);
            fd.append('type',this._insertion.type);
            fd.append('price',this._insertion.price.toString());
            fd.append('shipping',this._insertion.shipping.toString());
            fd.append('condition',this._insertion.condition);
            fd.append('state',this._insertion.state);
            fd.append('city',this._insertion.city);
            fd.append('description',this._insertion.description);
            fd.append(Constants.KEY_AJAX,this._insertion.ajax.toString());
            const request = fetch(InsertionRequest.INSERTION_URL,{
                method: 'POST',
                body: fd
            });
            request.then(r => {
                resolve(r.text());
            }).catch(err => {
                reject(InsertionRequest.ERR_MSG_INSERTIONEERROR);
            });
        });
    }

    //HTTP request for insert the insertion in MySql DB
    private createInsertion(): void{
        this._errno = 0;
        let dm,dmData : DialogMessageInterface,msgDialog: JQuery<HTMLElement>,resJson;
        if(this._insertion != null){
            //Insertion object is istantiated
            if(this.validateInsertion()){
                //All data are valid
                this.createPromise().then(res => {
                    //console.log(res); 
                    resJson = JSON.parse(res);
                    dmData = {
                        title: 'Nuova inserzione',
                        message: resJson.msg
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#'+dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click',()=>{
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                }).catch(err => {
                    console.warn(err);
                    dmData = {
                        title: 'Nuova inserzione',
                        message: err
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#'+dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click',()=>{
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                });
            }//if(this.validateInsertion()){
            else
                this._errno = InsertionRequest.ERR_INVALIDDATA;
        }//if(this._insertion != null){
        else
            this._errno = InsertionRequest.ERR_NOINSERTIONOBJECT;
    }

    //Check if Insertion model has required properties
    private validateInsertion(): boolean{
        let ok = false;
        let okName: boolean = (this._insertion.name != '');
        let okImage: boolean = (this._insertion.image != null);
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