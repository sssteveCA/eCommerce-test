import DialogMessage from "../dialog/dialogmessage";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import RecoveryRequest from "./recovery.request";
import RecoveryInterface from "./recovery.interface";
import { Constants } from "../constants/constants";

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
                message: obj[Constants.KEY_MESSAGE]
            }
            let dm: DialogMessage = new DialogMessage(dmData);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
            });
        });
    });
});