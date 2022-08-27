import TableBuilderInterface from "../interfaces/tablebuilder.interface";

//Print HTML orders table
export default class TableBuilder{
    private _id_container: string; //id of parent element where table is appended
    private _cart_data: object;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(data: TableBuilderInterface){
        this._id_container = data.id_container;
        this._cart_data = data.cart_data;
    }

    get cart_data(){return this._cart_data;}
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