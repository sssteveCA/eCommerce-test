export default interface OrderInterface{
    id: number; //Order id
    idc: number; //Customer id
    idp: number; //Product id
    idv: number; //Seller id
    date: string; //Order created date
    quantity: number; //Number of products in order
    total: number; //Total price
    payed?: boolean|null; //If order was payed
    tnx_id?: string|null; //Transaction id(null if order not payed yet)
    cart?: boolean|null; //If order was added in cart
}