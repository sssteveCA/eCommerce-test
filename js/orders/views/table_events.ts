import TableEventsInterface from "../interfaces/table_events.interface";

//Set the events for the orders table
export default class TableEvents{

    private _form_class: string; //The class of the form submitted
    private _button_classes: Array<string>; //button classes list when events occur

    constructor(data: TableEventsInterface){
        this._form_class = data.form_class;
        this._button_classes = data.button_classes;
    }

    get form_class(){return this._form_class;}
    get button_classes(){return this._button_classes;}

    private setEvents(): void{
        let button_classes: Array<string> = this._button_classes;
        let forms: JQuery = $('.'+this._form_class);
        forms.on('submit',function(ev){
            ev.preventDefault();
            let btn: JQuery = $('input[type=submit]:focus');
            let formId = $(this).attr('id');
            let idOrd = $('input[type=hidden][form='+formId+']').val(); 
            let data = {
                'idOrd': idOrd
            };
            if(btn.hasClass(button_classes[0])){
                //class of button order quantity
            }
            else if(btn.hasClass(button_classes[1])){
                //class of button order details
            }
            else if(btn.hasClass(button_classes[1])){
                //class of button order delete
            }

        });//forms.on('submit',function(ev){
    }
}