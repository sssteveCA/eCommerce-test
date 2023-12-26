
export default interface CartOrderInterface{
    ido: number; //Order id
    idp: number; //Product id
    idv: number; //Seller id of this product
    name: string; //Product name
    image: string; //URL of product image
    type: string; //Product category
    product_price: number; //Single product price
    quantity: number; //Number of products in this order
    shipping: number; //Shipping cost of this order
    total: number; //Total price of the order
}