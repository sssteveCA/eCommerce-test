import ProductInterface from "../interfaces/product.interface";

export default class Product{
    private _id: number; //Id of product
    private _idu: number; //Seller id of the product
    private _name: string; //Product name
    private _type: string; //Product type
    private _price: number; //Product price
    private _shipping: number; //Product shipping cost
    private _condition: string; //Product condition
    private _state: string; //The state where the product is located
    private _city: string; //The city where the product is located
    private _date: string; //The date where the insertion was added
    private _description: string; //Product description

    constructor(data: ProductInterface){
        this._id = data.id;
        this._idu = data.idu; 
        this._name = data.name; 
        this._type = data.type; 
        this._price = data.price;
        this._shipping = data.shipping;
        this._condition = data.condition;
        this._state = data.state;
        this._city = data.city;
        this._date = data.date;
        this._description = data.description;
    }

    get id(){return this._id;}
    get idu(){return this._idu;}
    get name(){return this._name;}
    get type(){return this._type;}
    get price(){return this._price;}
    get shipping(){return this._shipping;}
    get condition(){return this._condition;}
    get state(){return this._state;}
    get city(){return this._city;}
    get date(){return this._date;}
    get description(){return this._description;}

    set id(id){this._id = id;}
    set idu(idu){this._idu = idu;}
    set name(name){this._name = name;}
    set type(type){this._type = type;}
    set price(price){this._price = price;}
    set shipping(shipping){this._shipping = shipping;}
    set condition(condition){this._condition = condition;}
    set state(state){this._state = state;}
    set city(city){this._city = city;}
    set date(date){this._date = date;}
    set description(description){this._description = description;}
}