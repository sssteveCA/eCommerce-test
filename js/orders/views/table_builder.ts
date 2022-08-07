import TableBuilderInterface from "../interfaces/table_builder.interface";
import Order from "../models/order.model";


//Print HTML orders table
export default class TableBuilder{
    private _done: boolean;
    private _orders: Order[];
    private _msg: string;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(data: TableBuilderInterface){
        this._done = data.done;
        this._orders = data.orders;
        this._msg = data.msg;
    }

    get done(){return this._done;}
    get orders(){return this._orders;}
    get msg(){return this._msg;}
    get table(){return this._table;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}