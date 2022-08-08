import GetOrders from "./requests/getorders.js";
import TableBuilder from "./views/table_builder.js";
import TableEvents from "./views/table_events.js";
$(function () {
    getOrders();
});
export function getOrders() {
    let gos_data = {
        operation: 0
    };
    let get_orders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
        let errno = get_orders.errno;
        switch (errno) {
            case 0:
                let tb_data = {
                    id_container: 'ordiniT',
                    orders: get_orders.orders,
                };
                table(tb_data);
                break;
            case GetOrders.ERR_FETCH:
                break;
        }
    });
}
function table(data) {
    let table_builder = new TableBuilder(data);
    let te_data = {
        form_class: 'formOrder',
        button_classes: [
            'bQuantita',
            'bDettagli',
            'bElimina',
            'bCarrello'
        ],
        operations: {
            quantity: 3,
            details: 1,
            delete: 2,
            cart: 4
        }
    };
    let te = new TableEvents(te_data);
}
