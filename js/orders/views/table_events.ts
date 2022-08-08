import AddToCartInterface from "../interfaces/addtocart.interface";
import DeleteOrderInterface from "../interfaces/deleteorder.interface";
import EditQuantityInterface from "../interfaces/editquantity.interface";
import GetOrderInterface from "../interfaces/getorder.interface";
import {TableEventsInterface, Operations} from "../interfaces/table_events.interface";
import AddToCart from "../requests/addtocart.js";
import DeleteOrder from "../requests/deleteorder.js";
import EditQuantity from "../requests/editquantity.js";
import GetOrder from "../requests/getorder.js";

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
        let operations: Operations = this._operations;
        let forms: JQuery = $('.'+this._form_class);
        console.log(forms);
        let this_obj: TableEvents = this; //Referenche to this inside submit event
        forms.on('submit',function(ev){
            ev.preventDefault();
            console.log("submit");
            let btn: JQuery = $('button[type=submit]:focus');
            console.log(btn);
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form='+formId+']').val() as string; 
            if(btn.hasClass(button_classes[0])){
                //class of button order quantity
                this_obj.editQuantity(idOrd);
            }
            else if(btn.hasClass(button_classes[1])){
                //class of button order details
                let go_data: GetOrderInterface = {
                    id_order: idOrd as string,
                    operation: operations.quantity
                };
                let go: GetOrder = new GetOrder(go_data);
            }
            else if(btn.hasClass(button_classes[2])){
                //class of button order delete
                let do_data: DeleteOrderInterface = {
                    id_order: idOrd as string,
                    operation: operations.quantity
                };
                let del_ord: DeleteOrder = new DeleteOrder(do_data);
            }
            else if(btn.hasClass(button_classes[3])){
                //class of button add to cart
                let ac_data: AddToCartInterface = {
                    id_order: idOrd as string,
                    operation: operations.quantity
                };
                let ac: AddToCart = new AddToCart(ac_data);
            }
        });//forms.on('submit',function(ev){
    }

    //edit order quantity request
    private editQuantity(idOrd: string): void{
        console.log("editQuantity");
        let eq_data: EditQuantityInterface = {
            id_order: idOrd as string,
            operation: this._operations.quantity
        };
        let eq: EditQuantity = new EditQuantity(eq_data);
        eq.editQuantity().then(res => {

        });
    }
}