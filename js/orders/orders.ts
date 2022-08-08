import GetOrdersInterface from "./interfaces/getorders.interface";
import GetOrders from "./requests/getorders.js";

$(function(){
    let gos_data: GetOrdersInterface = {
        operation: '0'
    };
    let get_orders: GetOrders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
        console.log(get_orders.orders);
    });
});