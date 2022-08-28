import { TableEventsInterface, Operations, FormClasses
 } from "../interfaces/tableevents.interface";
 import DeleteCartOrderInterface from "../interfaces/deletecartorder.interface";
 import DeleteCartOrder from "../requests/deletecartorder";
 import DialogConfirmInterface from "../../dialog/dialogconfirm.interface";
 import DialogConfirm from "../../dialog/dialogconfirm.js";
 import { Constants } from "../../constants/constants.js";
 import { deleteOrderFromCart } from "../cart.js";

export default class TableEvents{

    private _form_classes: FormClasses; //Form classes for every operation
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        this._form_classes = data.form_classes;
        this._operations = data.operations;
        this.setEvents();
    }

    get form_classes(){return this._form_classes;}
    get operations(){return this._operations;}

    private setEvents(): void{
        let form_classes: FormClasses = this._form_classes;
        let delete_form: JQuery = $('.'+form_classes.delete);
        this.deleteFormEvent(delete_form);
        
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