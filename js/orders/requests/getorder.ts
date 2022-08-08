import GetOrderInterface from "../interfaces/getorder.interface";

export default class GetOrder{
    private _id_order: string; //Order to be obtained
    private _operation: string; //command to sent at backend to get the order

    constructor(data: GetOrderInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
}