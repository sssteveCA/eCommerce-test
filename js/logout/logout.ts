import { Constants } from "../constants/constants.js";
import DialogConfirmInterface from "../dialog/dialogconfirm.interface.js";
import DialogConfirm from "../dialog/dialogconfirm.js";


$(function(){
    //User logout from his account
    $('#logout a').on('click',function(e){
        e.preventDefault();
        let dcParams: DialogConfirmInterface = {
            title: 'Esci',
            message: Constants.MSG_CONFIRM_LOGOUT
        };
        let dc: DialogConfirm = new DialogConfirm(dcParams);
        let dcDialog = $('#'+dc.id);
        dc.btYes.on('click',() =>{
            //User press YES button
            //console.log("Sì");
            dcDialog.dialog('destroy');
            dcDialog.remove();
            window.location.href = 'funzioni/logout.php';

        });
        dc.btNo.on('click',() =>{
            //User press NO button
            //console.log("No");
            dcDialog.dialog('destroy');
            dcDialog.remove();
        });
    });
});