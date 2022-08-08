import {TableEventsInterface, Operations} from "../interfaces/table_events.interface";

//Set the events for the orders table
export default class TableEvents{

    private _form_class: string; //The class of the form submitted
    private _button_classes: Array<string>; //button classes list when events occur
    private _operations: Operations; //Orders table backend operations list

    constructor(data: TableEventsInterface){
        this._form_class = data.form_class;
        this._button_classes = data.button_classes;
        this._operations = data.operations;
    }

    get form_class(){return this._form_class;}
    get button_classes(){return this._button_classes;}
    get operations(){return this._operations;}

    private setEvents(): void{
        let button_classes: Array<string> = this._button_classes;
        let operations: Operations = this._operations;
        let forms: JQuery = $('.'+this._form_class);
        forms.on('submit',function(ev){
            ev.preventDefault();
            let btn: JQuery = $('input[type=submit]:focus');
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form='+formId+']').val(); 
            let data: any = {
                'idOrd': idOrd
            };
            if(btn.hasClass(button_classes[0])){
                //class of button order quantity
                data['oper'] = operations.quantity;
            }
            else if(btn.hasClass(button_classes[1])){
                //class of button order details
                data['oper'] = operations.delete;
            }
            else if(btn.hasClass(button_classes[2])){
                //class of button order delete
                data['oper'] = operations.delete;
            }
            else if(btn.hasClass(button_classes[3])){
                //class of button add to cart
                data['oper'] = operations.cart;
            }

        });//forms.on('submit',function(ev){
    }
}