import { TableEventsInterface, Operations, FormClasses
 } from "../interfaces/tableevents.interface";
 import DeleteCartOrderInterface from "../interfaces/deletecartorder.interface";
 import DeleteCartOrder from "../requests/deletecartorder";

export default class TableEvents{

    private _form_classes: FormClasses; //Form classes for every operation
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        this._form_classes = data.form_classes;
        this._operations = data.operations;
    }

    get form_classes(){return this._form_classes;}
    get operations(){return this._operations;}

}