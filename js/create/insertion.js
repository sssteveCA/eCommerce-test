import Insertion from "./insertion.model.js";
import InsertionController from "./insertion.controller.js";
import DialogConfirm from "../dialog/dialogconfirm.js";
$(function () {
    $('#fInsertion').on('submit', (ev) => {
        ev.preventDefault();
        let dcParams = {
            title: 'Nuova inserzione',
            message: 'Vuoi creare una nuova inserzione con i dati inseriti?'
        };
        let dc = new DialogConfirm(dcParams);
        let dcDialog = $('#' + dc.id);
        let image = $('#image')[0];
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:first-child').on('click', () => {
            //User press YES button
            console.log("Sì");
            dcDialog.dialog('destroy');
            dcDialog.remove();
            if (image != null) {
                //Element exists
                let imageFileList = image.files;
                if (imageFileList) {
                    //File list from input type file exists
                    let imageFile = imageFileList.item(0);
                    var data = {
                        idU: $('#idU').val(),
                        name: $('#name').val(),
                        image: imageFile,
                        type: $('#type').val(),
                        price: $('#price').val(),
                        shipping: $('#shipping').val(),
                        condition: $('input[name=condition]:checked').val(),
                        state: $('#state').val(),
                        city: $('#city').val(),
                        description: $('#description').val(),
                        ajax: true
                    };
                    //console.log(data);
                    let insertion = new Insertion(data);
                    let insertionController = new InsertionController(insertion);
                } //if(imageFileList){
            } //if(image != null){ 
        });
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:last-child').on('click', () => {
            //User press NO button
            console.log("No");
            dcDialog.dialog('destroy');
            dcDialog.remove();
        });
    });
});
