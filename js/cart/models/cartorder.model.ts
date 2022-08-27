import CartOrderInterface from "../interfaces/cartorder.interface";

export default class CartOrder{
    private _ido: number; //Order id
    private _idp: number; //Product id
    private _idv: number; //Seller id of this product
    private _image: string; //URL of product image
    private _type: string; //Product category
    private _product_price: number; //Single product price
    private _quantity: number; //Number of products in this order
    private _shipping: number; //Shipping cost of this order
    private _total: number; //Total price of the order

    constructor(data: CartOrderInterface){
        this._ido = data.ido;
        this._idp = data.idp;
        this._idv = data.idv;
        this._image = data.image;
        this._type = data.type;
        this._product_price = data.product_price;
        this._quantity = data.quantity;
        this._shipping = data.shipping;
        this._total = data.total;
    }

    get ido(){return this._ido;}
    get idp(){return this._idp;}
    get idv(){return this._idv;}
    get image(){return this._image;}
    get type(){return this._type;}
    get product_price(){return this._product_price;}
    get quantity(){return this._quantity;}
    get shipping(){return this._shipping;}
    get total(){return this._total;}

    set ido(ido: number){this._ido = ido;}
    set idp(idp: number){this._idp = idp;}
    set idv(idv: number){this._idv = idv;}
    set image(image: string){this._image = image;}
    set type(type: string){this._type = type;}
    set product_price(product_price: number){this._product_price = product_price;}
    set quantity(quantity: number){this._quantity = quantity;}
    set shipping(shipping: number){this._shipping = shipping;}
    set total(total: number){this._total = total;}
}