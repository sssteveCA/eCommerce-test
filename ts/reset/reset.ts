import DialogMessageInterface from "../dialog/dialogmessage.interface";
import ResetRequest from "./reset.request";
import ResetInterface from "./reset.interface";
import { showDialogMessage } from "../functions/functions";
import { Constants } from "../constants/constants";

$(()=>{
    $('#showPass').on('change',(e)=>{
        let thisCb: JQuery<HTMLInputElement> = $(e.currentTarget) as JQuery<HTMLInputElement>;
        if(thisCb.is(":checked")){
            $('#nuova').attr('type','text');
            $('#confNuova').attr('type','text');
        }
        else{
            $('#nuova').attr('type','password');
            $('#confNuova').attr('type','password');
        }
    });
    $('#fRecupera').on('submit',(e)=>{
        e.preventDefault();
        let spinner: JQuery<HTMLDivElement> = $('#spinner') as JQuery<HTMLDivElement>;
        let data: ResetInterface = {
            key: $('#chiave').val() as string,
            newPassword: $('#nuova').val() as string,
            confPassword: $('#confNuova').val() as string
        };
        let reset: ResetRequest = new ResetRequest(data);
        spinner.toggleClass("invisible");
        reset.resetPassword().then(obj => {
            spinner.toggleClass("invisible");
            let dmData: DialogMessageInterface = {
                title: 'Reimpostazione password',
                message: obj[Constants.KEY_MESSAGE]
            };
            showDialogMessage(dmData);
        });
    });
});