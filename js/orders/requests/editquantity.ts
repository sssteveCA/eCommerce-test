import EditQuantityInterface from "../interfaces/editquantity.interface";

export default class EditQuantity{
    private _id_order: string; //Order to be obtained
    private _operation: string; //command to sent at backend to edit order quantity

    constructor(data: EditQuantityInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
}