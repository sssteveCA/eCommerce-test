import DialogMessage from "../../dialog/dialogmessage.js";
import AddToCart from "../requests/addtocart.js";
import DeleteOrder from "../requests/deleteorder.js";
import EditQuantity from "../requests/editquantity.js";
import GetOrder from "../requests/getorder.js";
import { Constants } from "../../constants/constants.js";
import DialogConfirm from "../../dialog/dialogconfirm.js";
import * as ordersMain from "../orders.js";
//Set the events for the orders table
export default class TableEvents {
    constructor(data) {
        this._form_class = data.form_class;
        this._button_classes = data.button_classes;
        this._operations = data.operations;
        this.setEvents();
    }
    get form_class() { return this._form_class; }
    get button_classes() { return this._button_classes; }
    get operations() { return this._operations; }
    setEvents() {
        let button_classes = this._button_classes;
        let forms = $('.' + this._form_class);
        let this_obj = this; //Referenche to this inside submit event
        forms.on('submit', function (ev) {
            ev.preventDefault();
            let btn = $('button[type=submit]:focus');
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form=' + formId + ']').
                val();
            if (btn.hasClass(button_classes[0])) {
                //class of button order quantity
                let new_quantity = $('input[name=quantita][form=' + formId + ']').val();
                this_obj.editQuantity(idOrd, new_quantity);
            }
            else if (btn.hasClass(button_classes[1])) {
                //class of button order details
                this_obj.getOrder(idOrd);
            }
            else if (btn.hasClass(button_classes[2])) {
                //class of button order delete
                this_obj.deleteOrder(idOrd);
            }
            else if (btn.hasClass(button_classes[3])) {
                //class of button add to cart
                this_obj.addToCart(idOrd);
            }
        }); //forms.on('submit',function(ev){
    }
    //edit order quantity request
    editQuantity(idOrd, new_quantity) {
        let eq_data = {
            id_order: idOrd,
            operation: this._operations.quantity,
            quantity: new_quantity
        };
        let eq = new EditQuantity(eq_data);
        eq.editQuantity().then(obj => {
            let dm_data = {
                title: 'Modifica Quantità',
                message: obj['msg']
            };
            let dm = new DialogMessage(dm_data);
            dm.btOk.on('click', () => {
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if (obj['done'] == true) {
                    //Reload orders table only if edit quantity operation was done successfully
                    ordersMain.getOrders();
                }
            }); //dm.btOk.on('click',()=>{
        }); //eq.editQuantity().then(obj => {
    }
    getOrder(idOrd) {
        let go_data = {
            id_order: idOrd,
            operation: this._operations.details
        };
        let go = new GetOrder(go_data);
        go.getOrder().then(msg => {
            let dm_data = {
                title: "Informazioni sull' ordine",
                message: msg
            };
            let dm = new DialogMessage(dm_data);
            dm.btOk.on('click', () => {
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
            });
        });
    }
    deleteOrder(idOrd) {
        let dc_data = {
            title: 'Elimina ordine',
            message: Constants.MSG_CONFIRM_ORDERDELETE
        };
        let dc = new DialogConfirm(dc_data);
        dc.btYes.on('click', () => {
            dc.dialog.dialog('destroy');
            dc.dialog.remove();
            let do_data = {
                id_order: idOrd,
                operation: this._operations.delete
            };
            let del_ord = new DeleteOrder(do_data);
            del_ord.deleteOrder().then(obj => {
                let dm_data = {
                    title: 'Elimina ordine',
                    message: obj['msg']
                };
                let dm = new DialogMessage(dm_data);
                dm.btOk.on('click', () => {
                    dm.dialog.dialog('destroy');
                    dm.dialog.remove();
                    if (obj['done'] == true) {
                        //Reload orders table only if delete operation was done successfully
                        ordersMain.getOrders();
                    }
                });
            }); //del_ord.deleteOrder().then(obj => {
        }); //dc.btYes.on('click',()=>{
        dc.btNo.on('click', () => {
            dc.dialog.dialog('destroy');
            dc.dialog.remove();
        });
    }
    addToCart(idOrd) {
        let ac_data = {
            id_order: idOrd,
            operation: this._operations.cart
        };
        let ac = new AddToCart(ac_data);
        ac.AddToCart().then(obj => {
            let dm_data = {
                title: 'Aggiungi al carrello',
                message: obj['msg']
            };
            let dm = new DialogMessage(dm_data);
            dm.btOk.on('click', () => {
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if (obj['done'] == true) {
                    //Reload orders table only if add to cart operation was done successfully
                    ordersMain.getOrders();
                }
            });
        });
    }
}
