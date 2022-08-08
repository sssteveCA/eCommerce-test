var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
export default class AddToCart {
    constructor(data) {
        this._errno = 0;
        this._error = null;
        this._id_order = data.id_order;
        this._operation = data.operation;
    }
    get id_order() { return this._id_order; }
    get operation() { return this._operation; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case AddToCart.ERR_FETCH:
                this._error = AddToCart.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    AddToCart() {
        return __awaiter(this, void 0, void 0, function* () {
            let message = '';
            this._errno = 0;
            try {
                yield this.AddToCartPromise().then(res => {
                    //console.log(res);
                    let json = JSON.parse(res);
                    message = json['msg'];
                }).catch(err => {
                    throw err;
                });
            }
            catch (e) {
                this._errno = AddToCart.ERR_FETCH;
                message = AddToCart.ERR_FETCH_MSG;
            }
            return message;
        });
    }
    AddToCartPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                let body_params = `?idOrd=${this._id_order}&oper=${this._operation}`;
                fetch(AddToCart.ADDTOCART_URL + body_params).then(res => {
                    resolve(res.text());
                }).catch(err => {
                    reject(err);
                });
            });
        });
    }
}
AddToCart.ADDTOCART_URL = 'funzioni/orderMan.php';
//Error numbers
AddToCart.ERR_FETCH = 1;
//Error messages
AddToCart.ERR_FETCH_MSG = "Errore durante la richiesta dei dati";
