import DialogConfirmInterface from "../dialog/dialogconfirm.interface.js";
import DialogConfirm from "../dialog/dialogconfirm.js";

$(function(){
    //User logout from his account
    $('#logout a').on('click',function(e){
        e.preventDefault();
        let dcParams: DialogConfirmInterface = {
            title: 'Esci',
            message: 'Sei sicuro di voler abandonare la sessione?'
        };
        let dc = new DialogConfirm(dcParams);
        let dcDialog = $('#'+dc.id);
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:first-child').on('click',() =>{
            //User press YES button
            console.log("Sì");
            dcDialog.dialog('destroy');
            dcDialog.remove();
            window.location.href = 'funzioni/logout.php';

        });
        $('div.ui-dialog-buttonpane div.ui-dialog-buttonset > button:last-child').on('click',() =>{
            //User press NO button
            console.log("No");
            dcDialog.dialog('destroy');
            dcDialog.remove();
        });
    });
});