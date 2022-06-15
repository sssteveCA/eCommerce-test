var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import DialogMessage from "../dialog/dialogmessage.js";
//Create the Insertion in DB passing the Insertion object
export default class InsertionController {
    constructor(insertion) {
        this._errno = 0;
        this._error = null;
        this._insertion = insertion;
        this.createInsertion();
    }
    get insertion() { return this._insertion; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
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
    createPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                const headers = {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                };
                let fd = new FormData();
                fd.append('idU', this._insertion.idU.toString());
                fd.append('name', this._insertion.name);
                fd.append('image', this._insertion.image);
                fd.append('type', this._insertion.type);
                fd.append('price', this._insertion.price.toString());
                fd.append('shipping', this._insertion.shipping.toString());
                fd.append('condition', this._insertion.condition);
                fd.append('state', this._insertion.state);
                fd.append('city', this._insertion.city);
                fd.append('description', this._insertion.description);
                fd.append('ajax', this._insertion.ajax.toString());
                const request = fetch(InsertionController.INSERTION_URL, {
                    method: 'POST',
                    body: fd
                });
                request.then(r => {
                    resolve(r.text());
                }).catch(err => {
                    reject(InsertionController.ERR_MSG_INSERTIONEERROR);
                });
            });
        });
    }
    //HTTP request for insert the insertion in MySql DB
    createInsertion() {
        this._errno = 0;
        let dm, dmData, msgDialog, resJson;
        if (this._insertion != null) {
            //Insertion object is istantiated
            if (this.validateInsertion()) {
                //All data are valid
                this.createPromise().then(res => {
                    //console.log(res); 
                    resJson = JSON.parse(res);
                    dmData = {
                        title: 'Nuova inserzione',
                        message: resJson.msg
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#' + dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
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
                    msgDialog = $('#' + dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                });
            } //if(this.validateInsertion()){
            else
                this._errno = InsertionController.ERR_INVALIDDATA;
        } //if(this._insertion != null){
        else
            this._errno = InsertionController.ERR_NOINSERTIONOBJECT;
    }
    //Check if Insertion model has required properties
    validateInsertion() {
        let ok = false;
        let okName = (this._insertion.name != '');
        let okImage = (this._insertion.image != null);
        let okType = (this._insertion.type != '');
        let okPrice = (this._insertion.price > 0);
        let okShipping = (this._insertion.shipping > 0);
        let okCondition = (this._insertion.condition != '');
        let okState = (this._insertion.state != '');
        let okCity = (this._insertion.city != '');
        let okDescription = (this._insertion.description != '');
        if (okName && okImage && okType && okPrice && okShipping && okCondition && okState && okCity && okDescription) {
            ok = true;
        }
        return ok;
    }
}
//constants
InsertionController.INSERTION_URL = 'funzioni/upload.php';
//errors
InsertionController.ERR_INVALIDDATA = 1; //Data from form are invalid
InsertionController.ERR_NOINSERTIONOBJECT = 2; //Insertion object is null
InsertionController.ERR_MSG_INVALIDDATA = "Uno o più valori inseriti non sono validi";
InsertionController.ERR_MSG_NOINSERTIONOBJECT = "L'oggetto Insertion è uguale a null";
InsertionController.ERR_MSG_INSERTIONEERROR = "Errore durante l' inserimento dell'inserzione. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";
