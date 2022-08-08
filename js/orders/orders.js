import GetOrders from "./requests/getorders.js";
$(function () {
    getOrders();
});
function getOrders() {
    let gos_data = {
        operation: '0'
    };
    let get_orders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
        console.log(get_orders.orders);
        let errno = get_orders.errno;
        switch (errno) {
            case 0:
                break;
            case GetOrders.ERR_FETCH:
                break;
        }
    });
}
