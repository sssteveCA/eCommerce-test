import GetOrdersInterface from "./interfaces/getorders.interface";
import TableBuilderInterface from "./interfaces/table_builder.interface";
import { Operations, TableEventsInterface } from "./interfaces/table_events.interface";
import GetOrders from "./requests/getorders";
import TableBuilder from "./views/table_builder";
import TableEvents from "./views/table_events";

$(function(){
    getOrders();
});

export function getOrders(){
    let gos_data: GetOrdersInterface = {
        operation: 0
    };
    let get_orders: GetOrders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
        let errno: number = get_orders.errno;
        switch(errno){
            case 0:
                let tb_data: TableBuilderInterface = {
                    id_container: 'ordiniT',
                    orders: get_orders.orders,
                }
                table(tb_data);
                break;
            case GetOrders.ERR_FETCH:
                break;
        }
    });
}

function table(data: TableBuilderInterface){
    let table_builder: TableBuilder = new TableBuilder(data);
    let te_data: TableEventsInterface = {
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
    let te: TableEvents = new TableEvents(te_data);
}