import InsertionInterface from "./insertion.interface";

//Insert this model in DB
export default class Insertion{
    private _idU: number;
    private _name: string;
    private _image: File;
    private _type: string;
    private _price: number;
    private _shipping: number;
    private _condition: string;
    private _state: string;
    private _city: string;
    private _description: string;
    private _ajax: boolean;

   constructor(data: InsertionInterface){
    this._idU = data.idU;
    this._name = data.name;
    this._image = data.image;
    this._type = data.type;
    this._price = data.price;
    this._shipping = data.shipping;
    this._condition = data.condition;
    this._state = data.state;
    this._city = data.city;
    this._description = data.description;
    if(data.hasOwnProperty('ajax')){
        this._ajax = data.ajax as boolean;
    }
    else
        this._ajax = false;
   }//constructor(data: InsertionInterface){

   get idU(){return this._idU;}
   get name(){return this._name;}
   get image(){return this._image;}
   get type(){return this._type;}
   get price(){return this._price;}
   get shipping(){return this._shipping;}
   get condition(){return this._condition;}
   get state(){return this._state;}
   get city(){return this._city;}
   get description(){return this._description;}
   get ajax(){return this._ajax;} 
}