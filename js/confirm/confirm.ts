import { Constants } from "../constants/constants";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import { showDialogMessage } from "../functions/functions.js";
import ConfirmInterface from "./confirm.interface";
import ConfirmRequest from "./confirm.request.js";

$(()=>{
    $('#cart').on('submit',(ev)=>{
        ev.preventDefault();
        let confirmData: ConfirmInterface = {
            oper: 2,
            ido: $('#ido').val() as number,
            idp: $('#idp').val() as number
        }
       /*  console.log("confirmData => ");
        console.log(confirmData); */
        let confirmReq: ConfirmRequest = new ConfirmRequest(confirmData);
        confirmReq.confirmRequest().then(obj => {
            let dmData: DialogMessageInterface = {
                title: '',
                message: obj[Constants.KEY_MESSAGE]
            }
            showDialogMessage(dmData);
        });
    });
});