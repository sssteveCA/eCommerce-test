import AddToCart from "../requests/addtocart.js";
import DeleteOrder from "../requests/deleteorder.js";
import EditQuantity from "../requests/editquantity.js";
import GetOrder from "../requests/getorder.js";
//Set the events for the orders table
export default class TableEvents {
    constructor(data) {
        console.log(data);
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
        console.log(button_classes);
        let forms = $('.' + this._form_class);
        console.log(forms);
        let this_obj = this; //Referenche to this inside submit event
        forms.on('submit', function (ev) {
            ev.preventDefault();
            console.log("submit");
            let btn = $('button[type=submit]:focus');
            console.log(btn);
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
        console.log("editQuantity");
        let eq_data = {
            id_order: idOrd,
            operation: this._operations.quantity,
            quantity: new_quantity
        };
        console.log(eq_data);
        let eq = new EditQuantity(eq_data);
        eq.editQuantity().then(res => {
        });
    }
    getOrder(idOrd) {
        let go_data = {
            id_order: idOrd,
            operation: this._operations.details
        };
        let go = new GetOrder(go_data);
        go.getOrder().then(res => {
        });
    }
    deleteOrder(idOrd) {
        let do_data = {
            id_order: idOrd,
            operation: this._operations.delete
        };
        let del_ord = new DeleteOrder(do_data);
        del_ord.deleteOrder().then(msg => {
        });
    }
    addToCart(idOrd) {
        let ac_data = {
            id_order: idOrd,
            operation: this._operations.cart
        };
        let ac = new AddToCart(ac_data);
    }
}
