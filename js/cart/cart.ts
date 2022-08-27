import GetOrdersInCartInterface from "./interfaces/getordersincart.interface";
import GetOrdersInCart from "./requests/getordersincart.js";

$(()=>{
    let goic_data: GetOrdersInCartInterface = {
        operation: 1
    };
    let goic = new GetOrdersInCart(goic_data);
    goic.getOrdersInCart().then(obj => {
        console.log(goic.cart_orders);
    });//goic.getOrdersInCart().then(obj => {
});