var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
export default class GetOrder {
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
            case GetOrder.ERR_FETCH:
                this._error = GetOrder.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    getOrder() {
        return __awaiter(this, void 0, void 0, function* () {
            let message = '';
            let order = null;
            this._errno = 0;
            try {
                yield this.getOrderPromise().then(res => {
                    //console.log(res);
                    let json = JSON.parse(res);
                    console.log(json);
                }).catch(err => {
                    throw err;
                });
            }
            catch (e) {
                this._errno = GetOrder.ERR_FETCH;
                message = GetOrder.ERR_FETCH_MSG;
            }
            return message;
        });
    }
    getOrderPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                let body_params = `?idOrd=${this._id_order}&oper=${this._operation}`;
                fetch(GetOrder.GETORDER_URL + body_params).then(res => {
                    resolve(res.text());
                }).catch(err => {
                    reject(err);
                });
            });
        });
    }
}
GetOrder.GETORDER_URL = 'funzioni/orderMan.php';
//Error numbers
GetOrder.ERR_FETCH = 1;
//Error messages
GetOrder.ERR_FETCH_MSG = "Errore durante l'esecuzione dell'operazione'";
