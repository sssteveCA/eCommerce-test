import Insertion from "./insertion.model.js";
import InsertionController from "./insertion.controller.js";
import InsertionInterface from "./data.interface.js";
import DialogConfirmInterface from "../dialog/dialogconfirm.interface.js";
import DialogConfirm from "../dialog/dialogconfirm.js";

$(function(){
    $('#fInsertion').on('submit',(ev)=>{
        ev.preventDefault();
        let dcParams: DialogConfirmInterface = {
            title: 'Nuova inserzione',
            message: 'Vuoi creare una nuova inserzione con i dati inseriti?'
        };
        let dc: DialogConfirm = new DialogConfirm(dcParams);
        let dcDialog: JQuery<HTMLElement> = $('#'+dc.id);
        let image = <HTMLInputElement>$('#image')[0];
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:first-child').on('click',()=>{
            //User press YES button
            console.log("Sì");
            dcDialog.dialog('destroy');
            dcDialog.remove();
            if(image != null){
                //Element exists
                let imageFileList = image.files as FileList;
                if(imageFileList){
                    //File list from input type file exists
                    let imageFile = imageFileList.item(0) as File;
                    var data: InsertionInterface = {
                        idU: $('#idU').val() as number,
                        name: $('#name').val() as string,
                        image: imageFile,
                        type: $('#type').val() as string,
                        price: $('#price').val() as number,
                        shipping: $('#shipping').val() as number,
                        condition: $('input[name=condition]:checked').val() as string,
                        state: $('#state').val() as string,
                        city: $('#city').val() as string,
                        description: $('#description').val() as string,
                        ajax: true
                    };
                    //console.log(data);
                    let insertion = new Insertion(data);
                    let insertionController = new InsertionController(insertion);
                }//if(imageFileList){
            }//if(image != null){ 
        });
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:last-child').on('click',()=>{
            //User press NO button
            console.log("No");
            dcDialog.dialog('destroy');
            dcDialog.remove();
        }); 
    });
});