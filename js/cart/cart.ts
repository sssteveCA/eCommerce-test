import DialogMessage from "../dialog/dialogmessage.js";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import DeleteCartOrderInterface from "./interfaces/deletecartorder.interface";
import GetCartOrdersInterface from "./interfaces/getcartorders.interface";
import {TableBuilderInterface} from "./interfaces/tablebuilder.interface";
import { TableEventsInterface } from "./interfaces/tableevents.interface";
import DeleteCartOrder from "./requests/deletecartorder.js";
import GetCartOrders from "./requests/getcartorders.js";
import TableBuilder from "./views/tablebuilder.js";
import TableEvents from "./views/tableevents.js";
import paypalCheckout from "./checkout/paypalcheckout.js";

$(()=>{
    let gco_data: GetCartOrdersInterface = {
        operation: 1
    };
    fGetCartOrders(gco_data);
    
});

/**
 * User clicks in 'pay now' button 
 * @param sbn 
 * @param cId 
 * @param currency 
 * @param seller 
 * @param seller_id 
 */
export function cartCheckout(sbn, clientId, currency,seller,seller_id): void{
    paypalCheckout(sbn,clientId,currency,seller,seller_id);
}

/**
 * Remove an order from cart
 * @param dco_data 
 */
export function deleteOrderFromCart(dco_data: DeleteCartOrderInterface): void{
    let dco: DeleteCartOrder = new DeleteCartOrder(dco_data);
    dco.deleteCartOrder().then(obj => {
        console.log(obj);
        let dm_data: DialogMessageInterface = {
            title: 'Rimuovi dal carrello',
            message: obj['msg']
        };
        let dm: DialogMessage = new DialogMessage(dm_data);
        dm.btOk.on('click',()=>{
            dm.dialog.dialog('destroy');
            dm.dialog.remove();
            if(obj['done'] === true){
                let gco_data: GetCartOrdersInterface = {
                    operation: 1
                };
                fGetCartOrders(gco_data);
            }//if(obj['done'] === true){
        });
    });
}

/**
 * Get user orders added to cart
 * @param gco_data 
 */
export function fGetCartOrders(gco_data: GetCartOrdersInterface): void{
    let gco = new GetCartOrders(gco_data);
    gco.getCartOrders().then(obj => {
        let errno = gco.errno;
        switch(errno){
            case 0:
                let tb_data: TableBuilderInterface = {
                    confirm_params: {
                        currency: 'EUR',
                        sbn_code: obj['sbn_code']
                    },
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
        confirm_params: {
            cart_data: tab.cart_data,
            currency: tab.confirm_params.currency,
            sbn_code: tab.confirm_params.sbn_code
        },
        button_classes: {
            confirm: 'confirmButton'
        },
        form_classes: {
            delete: 'fElim'
        },
        operations: {
            delete: 3
        }
    };
    let tab_ev: TableEvents = new TableEvents(te_data);
}