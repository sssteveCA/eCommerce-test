import DeleteOrderInterface from "../interfaces/deleteorder.interface";

export default class DeleteOrder{
    private _id_order: number; //Order to be deleted
    private _operation: number; //command to sent at backend to delete the order 

    constructor(data: DeleteOrderInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
}