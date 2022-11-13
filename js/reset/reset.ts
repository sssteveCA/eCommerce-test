import DialogMessage from "../dialog/dialogmessage.js";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import ResetController from "./reset.controller.js";
import ResetInterface from "./reset.interface";

$(()=>{
    $('#fRecupera').on('submit',(e)=>{
        e.preventDefault();
        let data: ResetInterface = {
            key: $('#chiave').val() as string,
            newPassword: $('#nuova').val() as string,
            confPassword: $('#confNuova').val() as string
        };
        let reset: ResetController = new ResetController(data);
        reset.resetPassword().then(obj => {
            let dmData: DialogMessageInterface = {
                title: 'Reimpostazione password',
                message: obj["msg"]
            };
            let dm: DialogMessage = new DialogMessage(dmData);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
            });
        });
    });
});