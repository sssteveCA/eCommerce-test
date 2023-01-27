import DialogMessage from "../dialog/dialogmessage.js";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import RecoveryRequest from "./recovery.request.js";
import RecoveryInterface from "./recovery.interface";

$(()=>{
    $('#fRecupera').on('submit',(ev)=>{
        ev.preventDefault();
        let spinner: JQuery<HTMLDivElement> = $('#spinner') as JQuery<HTMLDivElement>;
        let recoveryData: RecoveryInterface = {
            email: $('#email').val() as string
        };
        let rc: RecoveryRequest = new RecoveryRequest(recoveryData);
        spinner.toggleClass("invisible");
        rc.sendRecoveryMail().then(obj => {
            spinner.toggleClass("invisible");
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