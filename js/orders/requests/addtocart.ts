import AddToCartInterface from "../interfaces/addtocart.interface";

export default class AddToCart{
    private _id_order: string; //Order to be added to cart
    private _operation: string; //command to sent at backend to add order to cart

    constructor(data: AddToCartInterface){
        this._id_order = data.id_order;
        this._operation = data.operation;
    }

    get id_order(){return this._id_order;}
    get operation(){return this._operation;}
}