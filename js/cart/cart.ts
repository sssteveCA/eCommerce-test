import GetCartOrdersInterface from "./interfaces/getcartorders.interface";
import TableBuilderInterface from "./interfaces/tablebuilder.interface";
import { TableEventsInterface } from "./interfaces/tableevents.interface";
import GetCartOrders from "./requests/getcartorders";
import TableBuilder from "./views/tablebuilder.js";
import TableEvents from "./views/tableevents";

$(()=>{
    let gco_data: GetCartOrdersInterface = {
        operation: 1
    };
    fGetCartOrders(gco_data);
    
});

/**
 * Get user orders added to cart
 * @param gco_data 
 */
function fGetCartOrders(gco_data: GetCartOrdersInterface): void{
    let gco = new GetCartOrders(gco_data);
    gco.getCartOrders().then(obj => {
        let errno = gco.errno;
        switch(errno){
            case 0:
                let tb_data: TableBuilderInterface = {
                    id_container: 'carrello',
                    cart_data: obj['carrello']
                };
                table(tb_data);
                break;
            case GetCartOrders.ERR_FETCH:
                break;
        }
    });//goic.getOrdersInCart().then(obj => {
}

/**
 * Print User orders in cart table with response data obtained
 * @param tb_data 
 */
function table(tb_data: TableBuilderInterface): void{
    let tab: TableBuilder = new TableBuilder(tb_data);
    let te_data: TableEventsInterface = {
        form_classes: {
            delete: 'fElim'
        },
        operations: {
            delete: 3
        }
    };
    let tab_ev: TableEvents = new TableEvents(te_data);
}