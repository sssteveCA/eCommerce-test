var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
//Get all user orders
export default class GetOrders {
    constructor() {
        this._errno = 0;
        this._error = null;
    }
    get orders() { return this._orders; }
    get length() { return this._length; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case GetOrders.ERR_FETCH:
                this._error = GetOrders.ERR_FETCH_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    getOrders() {
        return __awaiter(this, void 0, void 0, function* () {
            let response = {};
            try {
                yield this.getOrdersPromise().then(res => {
                    console.log(res);
                    response = JSON.parse(res);
                }).catch(err => {
                    throw err;
                });
            }
            catch (e) {
                this._errno = GetOrders.ERR_FETCH;
                response = {
                    done: false,
                    msg: GetOrders.ERR_FETCH_MSG
                };
            }
            return response;
        });
    }
    getOrdersPromise() {
        return __awaiter(this, void 0, void 0, function* () {
            let response = yield new Promise((resolve, reject) => {
                fetch(GetOrders.GETORDERS_URL + '?oper=0').then(res => {
                    resolve(res.text());
                }).catch(err => {
                    reject(err);
                });
            });
            return response;
        });
    }
}
GetOrders.GETORDERS_URL = 'funzioni/orderMan.php';
//Error numbers
GetOrders.ERR_FETCH = 1;
//Error messages
GetOrders.ERR_FETCH_MSG = "Errore durante la richiesta dei dati";
