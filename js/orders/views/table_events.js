import AddToCart from "../requests/addtocart";
import DeleteOrder from "../requests/deleteorder";
import EditQuantity from "../requests/editquantity";
import GetOrder from "../requests/getorder";
//Set the events for the orders table
export default class TableEvents {
    constructor(data) {
        this._form_class = data.form_class;
        this._button_classes = data.button_classes;
        this._operations = data.operations;
    }
    get form_class() { return this._form_class; }
    get button_classes() { return this._button_classes; }
    get operations() { return this._operations; }
    setEvents() {
        let button_classes = this._button_classes;
        let operations = this._operations;
        let forms = $('.' + this._form_class);
        let this_obj = this; //Referenche to this inside submit event
        forms.on('submit', function (ev) {
            ev.preventDefault();
            let btn = $('input[type=submit]:focus');
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form=' + formId + ']').val();
            if (btn.hasClass(button_classes[0])) {
                //class of button order quantity
                this_obj.editQuantity(idOrd);
            }
            else if (btn.hasClass(button_classes[1])) {
                //class of button order details
                let go_data = {
                    id_order: idOrd,
                    operation: operations.quantity
                };
                let go = new GetOrder(go_data);
            }
            else if (btn.hasClass(button_classes[2])) {
                //class of button order delete
                let do_data = {
                    id_order: idOrd,
                    operation: operations.quantity
                };
                let del_ord = new DeleteOrder(do_data);
            }
            else if (btn.hasClass(button_classes[3])) {
                //class of button add to cart
                let ac_data = {
                    id_order: idOrd,
                    operation: operations.quantity
                };
                let ac = new AddToCart(ac_data);
            }
        }); //forms.on('submit',function(ev){
    }
    //edit order quantity request
    editQuantity(idOrd) {
        console.log("editQuantity");
        let eq_data = {
            id_order: idOrd,
            operation: this._operations.quantity
        };
        let eq = new EditQuantity(eq_data);
    }
}
