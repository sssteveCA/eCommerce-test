var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
export default class EditQuantity {
    constructor(data) {
        this._errno = 0;
        this._error = null;
        this._id_order = data.id_order;
        this._operation = data.operation;
        this._quantity = data.quantity;
    }
    get id_order() { return this._id_order; }
    get operation() { return this._operation; }
    get quantity() { return this._quantity; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case EditQuantity.ERR_FETCH:
                this._error = EditQuantity.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    editQuantity() {
        return __awaiter(this, void 0, void 0, function* () {
            let message = '';
            this._errno = 0;
            try {
                yield this.editQuantityPromise().then(res => {
                    //console.log(res);
                    let json = JSON.parse(res);
                    message = json['msg'];
                });
            }
            catch (e) {
                this._errno = EditQuantity.ERR_FETCH;
                message = EditQuantity.ERR_FETCH_MSG;
            }
            return message;
        });
    }
    editQuantityPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                let body_params = `?idOrd=${this._id_order}&oper=${this._operation}&quantita=${this._quantity}`;
                fetch(EditQuantity.EDITQUANTITY_URL + body_params).then(res => {
                    resolve(res.text());
                }).catch(err => {
                    reject(err);
                });
            });
        });
    }
}
EditQuantity.EDITQUANTITY_URL = 'funzioni/orderMan.php';
//Error numbers
EditQuantity.ERR_FETCH = 1;
//Error messages
EditQuantity.ERR_FETCH_MSG = "Errore durante l'esecuzione dell'operazione'";
