export default class Order {
    constructor(data) {
        this._id = data.id;
        this._idc = data.idc;
        this._idp = data.idp;
        this._idv = data.idv;
        this._date = data.date;
        this._quantity = data.quantity;
        this._total = data.total;
        if (data.payed)
            this._payed = data.payed;
        else
            this._payed = false;
        if (data.tnx_id)
            this._tnx_id = data.tnx_id;
        else
            this._tnx_id = null;
        if (data.cart)
            this._cart = data.cart;
        else
            this._cart = false;
    }
    get id() { return this._id; }
    get idc() { return this._idc; }
    get idp() { return this._idp; }
    get idv() { return this._idv; }
    get date() { return this._date; }
    get quantity() { return this._quantity; }
    get total() { return this._total; }
    get payed() { return this._payed; }
    get tnx_id() { return this._tnx_id; }
    get cart() { return this._cart; }
    set id(id) { this._id = id; }
    set idc(idc) { this._idc = idc; }
    set idp(idp) { this._idp = idp; }
    set idv(idv) { this._idv = idv; }
    set date(date) { this._date = date; }
    set quantity(quantity) { this._quantity = quantity; }
    set total(total) { this._total = total; }
    set payed(payed) { this._payed = payed; }
    set tnx_id(tnx_id) { this._tnx_id = tnx_id; }
    set cart(cart) { this._cart = cart; }
}
