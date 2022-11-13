import DialogMessage from "../dialog/dialogmessage.js";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import RecoveryController from "./recovery.controller.js";
import RecoveryInterface from "./recovery.interface";

$(()=>{
    $('#fRecupera').on('submit',(ev)=>{
        ev.preventDefault();
        let recoveryData: RecoveryInterface = {
            email: $('#email').val() as string
        };
        let rc: RecoveryController = new RecoveryController(recoveryData);
        rc.sendRecoveryMail().then(obj => {
            let dmData: DialogMessageInterface = {
                title: 'Recupero account',
                message: obj["msg"]
            }
            let dm: DialogMessage = new DialogMessage(dmData);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
            });
        });
    });
});