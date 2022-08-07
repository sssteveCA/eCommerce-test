//Print HTML orders table
export default class TableBuilder {
    constructor(data) {
        this._table = ''; //HTML table
        this._errno = 0;
        this._error = null;
        this._done = data.done;
        this._orders = data.orders;
        this._msg = data.msg;
    }
    get done() { return this._done; }
    get orders() { return this._orders; }
    get msg() { return this._msg; }
    get table() { return this._table; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}
