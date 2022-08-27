import { TableEventsInterface, Operations, FormClasses
 } from "../interfaces/tableevents.interface";
 import DeleteCartOrderInterface from "../interfaces/deletecartorder.interface";
 import DeleteCartOrder from "../requests/deletecartorder";
 import DialogConfirmInterface from "../../dialog/dialogconfirm.interface";
 import DialogConfirm from "../../dialog/dialogconfirm";
 import { Constants } from "../../constants/constants";

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
        console.log(delete_form);
        delete_form.on('submit',function(ev){
            ev.preventDefault();
            let dc_data: DialogConfirmInterface = {
                title: 'Rimuovi dal carrello',
                message: Constants.MSG_CONFIRM_REMOVEFROMCART
            };
            let dc: DialogConfirm = new DialogConfirm(dc_data);
            dc.btYes.on('click',()=>{
                let input: JQuery = $(this).find('input[name=ido]');
                console.log(input);
            });
            dc.btNo.on('click',()=>{
                dc.dialog.dialog('remove');
                dc.dialog.remove();
            });
        });//delete_form.on('submit',function(ev){
    }

}