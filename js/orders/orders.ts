import GetOrdersInterface from "./interfaces/getorders.interface";
import TableBuilderInterface from "./interfaces/table_builder.interface";
import GetOrders from "./requests/getorders.js";
import TableBuilder from "./views/table_builder.js";

$(function(){
    getOrders();
});

function getOrders(){
    let gos_data: GetOrdersInterface = {
        operation: '0'
    };
    let get_orders: GetOrders = new GetOrders(gos_data);
    get_orders.getOrders().then(obj => {
        console.log(get_orders.orders);
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
}