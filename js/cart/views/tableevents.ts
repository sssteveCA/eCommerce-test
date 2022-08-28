import { TableEventsInterface, Operations, FormClasses, ButtonClasses, TeConfirmParams
 } from "../interfaces/tableevents.interface";
 import DeleteCartOrderInterface from "../interfaces/deletecartorder.interface";
 import DeleteCartOrder from "../requests/deletecartorder";
 import DialogConfirmInterface from "../../dialog/dialogconfirm.interface";
 import DialogConfirm from "../../dialog/dialogconfirm.js";
 import { Constants } from "../../constants/constants.js";
 import { deleteOrderFromCart,cartCheckout } from "../cart.js";

export default class TableEvents{

    private _button_classes: ButtonClasses;
    private _confirm_params: TeConfirmParams;
    private _form_classes: FormClasses; //Form classes for every operation
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        this._button_classes = data.button_classes;
        this._confirm_params = data.confirm_params;
        console.log("tableevents.ts confirm params");
        console.log(this._confirm_params);
        this._form_classes = data.form_classes;
        this._operations = data.operations;
        this.setEvents();
    }

    get button_classes(){return this._button_classes;}
    get confirm_params(){return this._confirm_params;}
    get form_classes(){return this._form_classes;}
    get operations(){return this._operations;}

    private setEvents(): void{
        let delete_form: JQuery = $('.'+this._form_classes.delete);
        this.deleteFormEvent(delete_form);
        let confirm_button: JQuery = $('.'+this._button_classes.confirm);
        this.confirmButtonEvent(confirm_button);
    }

    /**
     * Register event on Pay cart order button click
     * @param confirm_button 
     */
    private confirmButtonEvent(confirm_button: JQuery): void{
        let this_obj: TableEvents = this;
        confirm_button.on('click', function(ev){
            let inputId = ev.target.id;
            let regex = /([0-9]+)$/;
            let match: any = regex.exec(inputId);
            let sellerId = match[1];
            let seller = this_obj._confirm_params.cart_data[sellerId];
            let clientId = this_obj._confirm_params.cart_data[sellerId][0]['clientId'];
            let sbn = this_obj._confirm_params.sbn_code;
            let currency = this_obj._confirm_params.currency;
            cartCheckout(sbn,clientId,currency,seller,sellerId);
        });
    }

    /**
     * Register event on remove item from cart form submit
     * @param delete_form 
     */
    private deleteFormEvent(delete_form: JQuery): void{
        let this_obj: TableEvents = this;
        delete_form.on('submit',function(ev){
            ev.preventDefault();
            let dc_data: DialogConfirmInterface = {
                title: 'Rimuovi dal carrello',
                message: Constants.MSG_CONFIRM_REMOVEFROMCART
            };
            let dc: DialogConfirm = new DialogConfirm(dc_data);
            dc.btYes.on('click',()=>{
                dc.dialog.dialog('destroy');
                dc.dialog.remove();
                let input: JQuery = $(this).find('input[name=ido]');
                let order_id: number = input.val() as number;
                let dco_data: DeleteCartOrderInterface = {
                    order_id: order_id,
                    operation: this_obj._operations.delete
                };
                deleteOrderFromCart(dco_data);
            });
            dc.btNo.on('click',()=>{
                dc.dialog.dialog('destroy');
                dc.dialog.remove();
            });
        });//delete_form.on('submit',function(ev){
    }

}