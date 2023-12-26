export default interface ProductInterface{
    id: number; //Id of product
    idu: number; //Seller id of the product
    name: string; //Product name
    type: string; //Product type
    price: number; //Product price
    shipping: number; //Product shipping cost
    condition: string; //Product condition
    state: string; //The state where the product is located
    city: string; //The city where the product is located
    date: string; //The date where the insertion was added
    description: string; //Product description
}