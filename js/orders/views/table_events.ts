import DialogMessage from "../../dialog/dialogmessage.js";
import DialogMessageInterface from "../../dialog/dialogmessage.interface";
import AddToCartInterface from "../interfaces/addtocart.interface";
import DeleteOrderInterface from "../interfaces/deleteorder.interface";
import EditQuantityInterface from "../interfaces/editquantity.interface";
import GetOrderInterface from "../interfaces/getorder.interface";
import {TableEventsInterface, Operations} from "../interfaces/table_events.interface";
import AddToCart from "../requests/addtocart.js";
import DeleteOrder from "../requests/deleteorder.js";
import EditQuantity from "../requests/editquantity.js";
import GetOrder from "../requests/getorder.js";
import DialogConfirmInterface from "../../dialog/dialogconfirm.interface";
import { Constants } from "../../constants/constants.js";
import DialogConfirm from "../../dialog/dialogconfirm.js";

//Set the events for the orders table
export default class TableEvents{

    private _form_class: string; //The class of the form submitted
    private _button_classes: Array<string>; //button classes list when events occur
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        console.log(data);
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
        console.log(button_classes);
        let forms: JQuery = $('.'+this._form_class);
        console.log(forms);
        let this_obj: TableEvents = this; //Referenche to this inside submit event
        forms.on('submit',function(ev){
            ev.preventDefault();
            console.log("submit");
            let btn: JQuery = $('button[type=submit]:focus');
            console.log(btn);
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
        console.log("editQuantity");
        let eq_data: EditQuantityInterface = {
            id_order: idOrd as number,
            operation: this._operations.quantity,
            quantity: new_quantity
        };
        console.log(eq_data);
        let eq: EditQuantity = new EditQuantity(eq_data);
        eq.editQuantity().then(msg => {
            let dm_data: DialogMessageInterface = {
                title: 'Modifica Quantità',
                message: msg
            };
            let dm: DialogMessage = new DialogMessage(dm_data);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
            });
        });
    }

    private getOrder(idOrd: number): void{
        let go_data: GetOrderInterface = {
            id_order: idOrd as number,
            operation: this._operations.details
        };
        let go: GetOrder = new GetOrder(go_data);
        go.getOrder().then(res => {

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
        });
        dc.btNo.on('click',()=>{
            dc.dialog.dialog('destroy');
            dc.dialog.remove();
        });
        /* let do_data: DeleteOrderInterface = {
            id_order: idOrd as number,
            operation: this._operations.delete
        };
        let del_ord: DeleteOrder = new DeleteOrder(do_data);
        del_ord.deleteOrder().then(msg => {

        }); */
    }

    private addToCart(idOrd: number): void{
        let ac_data: AddToCartInterface = {
            id_order: idOrd as number,
            operation: this._operations.cart
        };
        let ac: AddToCart = new AddToCart(ac_data);
        ac.AddToCart().then(msg => {

        });
    }
}