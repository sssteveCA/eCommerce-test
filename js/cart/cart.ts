import GetOrdersInCartInterface from "./interfaces/getordersincart.interface";
import GetOrdersInCart from "./requests/getordersincart";

$(()=>{
    let goic_data: GetOrdersInCartInterface = {
        operation: 1
    };
    let goic = new GetOrdersInCart(goic_data);
    goic.getOrdersInCart().then(obj => {

    });//goic.getOrdersInCart().then(obj => {
});