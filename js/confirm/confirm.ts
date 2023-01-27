import DialogMessageInterface from "../dialog/dialogmessage.interface";
import ConfirmInterface from "./confirm.interface";
import ConfirmRequest from "./confirm.request";

$(()=>{
    $('#cart').on('submit',(ev)=>{
        ev.preventDefault();
        let confirmData: ConfirmInterface = {
            oper: 2,
            ido: $('#ido').val() as number,
            idp: $('#idp').val() as number
        }
        let confirmReq: ConfirmRequest = new ConfirmRequest(confirmData);
        confirmReq.confirmRequest().then(obj => {
            let dmData: DialogMessageInterface = {
                title: '',
                message: obj["msg"]
            }
        });
    });
});