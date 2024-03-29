import DialogMessage from "../../dialog/dialogmessage";
import DialogMessageInterface from "../../dialog/dialogmessage.interface";
import AddToCartInterface from "../interfaces/addtocart.interface";
import DeleteOrderInterface from "../interfaces/deleteorder.interface";
import EditQuantityInterface from "../interfaces/editquantity.interface";
import GetOrderInterface from "../interfaces/getorder.interface";
import {TableEventsInterface, Operations} from "../interfaces/table_events.interface";
import AddToCart from "../requests/addtocart";
import DeleteOrder from "../requests/deleteorder";
import EditQuantity from "../requests/editquantity";
import GetOrder from "../requests/getorder";
import DialogConfirmInterface from "../../dialog/dialogconfirm.interface";
import { Constants } from "../../constants/constants";
import DialogConfirm from "../../dialog/dialogconfirm";
import * as ordersMain from "../orders";
import { showDialogMessage } from "../../functions/functions";

//Set the events for the orders table
export default class TableEvents{

    private _form_class: string; //The class of the form submitted
    private _button_classes: Array<string>; //button classes list when events occur
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        this._form_class = data.form_class;
        this._button_classes = data.button_classes;
        this._operations = data.operations;
        this.setEvents();
    }

    get form_class(){return this._form_class;}
    get button_classes(){return this._button_classes;}
    get operations(){return this._operations;}

    private setEvents(): void{
        let button_classes: Array<string> = this._button_classes;
        let forms: JQuery = $('.'+this._form_class);
        let this_obj: TableEvents = this; //Referenche to this inside submit event
        forms.on('submit',function(ev){
            ev.preventDefault();
            let btn: JQuery = $('button[type=submit]:focus');
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form='+formId+']').
            val() as number; 
            if(btn.hasClass(button_classes[0])){
                //class of button order quantity
                let new_quantity: number = $('input[name=quantita][form='+formId+']').val() as number;
                this_obj.editQuantity(idOrd,new_quantity);
            }
            else if(btn.hasClass(button_classes[1])){
                //class of button order details
                this_obj.getOrder(idOrd);
            }
            else if(btn.hasClass(button_classes[2])){
                //class of button order delete
                this_obj.deleteOrder(idOrd);
            }
            else if(btn.hasClass(button_classes[3])){
                //class of button add to cart
                this_obj.addToCart(idOrd);
            }
        });//forms.on('submit',function(ev){
    }

    //edit order quantity request
    private editQuantity(idOrd: number, new_quantity: number): void{
        let eq_data: EditQuantityInterface = {
            id_order: idOrd as number,
            operation: this._operations.quantity,
            quantity: new_quantity
        };
        let eq: EditQuantity = new EditQuantity(eq_data);
        eq.editQuantity().then(obj => {
            let dm_data: DialogMessageInterface = {
                title: 'Modifica Quantità',
                message: obj[Constants.KEY_MESSAGE]
            };
            let dm: DialogMessage = new DialogMessage(dm_data);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if(obj[Constants.KEY_DONE] == true){
                    //Reload orders table only if edit quantity operation was done successfully
                    ordersMain.getOrders();
                }
            });//dm.btOk.on('click',()=>{
        });//eq.editQuantity().then(obj => {
    }

    private getOrder(idOrd: number): void{
        let go_data: GetOrderInterface = {
            id_order: idOrd as number,
            operation: this._operations.details
        };
        let go: GetOrder = new GetOrder(go_data);
        go.getOrder().then(msg => {
            let dmData: DialogMessageInterface = {
                title: "Informazioni sull' ordine",
                message: msg
            };
            showDialogMessage(dmData)
        });
    }

    private deleteOrder(idOrd: number): void{
        let dc_data: DialogConfirmInterface = {
            title: 'Elimina ordine',
            message: Constants.MSG_CONFIRM_ORDERDELETE
        };
        let dc: DialogConfirm = new DialogConfirm(dc_data);
        dc.btYes.on('click',()=>{
            dc.dialog.dialog('destroy');
            dc.dialog.remove();
            let do_data: DeleteOrderInterface = {
                id_order: idOrd as number,
                operation: this._operations.delete
            };
            let del_ord: DeleteOrder = new DeleteOrder(do_data);
            del_ord.deleteOrder().then(obj => {
                let dm_data: DialogMessageInterface = {
                    title: 'Elimina ordine',
                    message: obj[Constants.KEY_MESSAGE]
                };
                let dm: DialogMessage = new DialogMessage(dm_data);
                dm.btOk.on('click',()=>{
                    dm.dialog.dialog('destroy');
                    dm.dialog.remove();
                    if(obj[Constants.KEY_DONE] == true){
                        //Reload orders table only if delete operation was done successfully
                        ordersMain.getOrders();
                    }
                });
            });//del_ord.deleteOrder().then(obj => {
        });//dc.btYes.on('click',()=>{
        dc.btNo.on('click',()=>{
            dc.dialog.dialog('destroy');
            dc.dialog.remove();
        });
        
    }

    private addToCart(idOrd: number): void{
        let ac_data: AddToCartInterface = {
            id_order: idOrd as number,
            operation: this._operations.cart
        };
        let ac: AddToCart = new AddToCart(ac_data);
        ac.AddToCart().then(obj => {
            let dm_data: DialogMessageInterface = {
                title: 'Aggiungi al carrello',
                message: obj[Constants.KEY_MESSAGE]
            };
            let dm: DialogMessage = new DialogMessage(dm_data);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if(obj[Constants.KEY_DONE] == true){
                    //Reload orders table only if add to cart operation was done successfully
                    ordersMain.getOrders();
                }
            });
        });
    }
}