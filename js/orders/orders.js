import GetOrders from "./requests/getorders";
$(function () {
    let gos_data = {
        operation: '0'
    };
    let get_orders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
    });
});
