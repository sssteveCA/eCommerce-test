import DialogMessage from "../dialog/dialogmessage";
import DialogMessageInterface from "../dialog/dialogmessage.interface";

export function showDialogMessage(dmData: DialogMessageInterface){
    let dm: DialogMessage = new DialogMessage(dmData);
    dm.btOk.on('click',()=>{
        dm.dialog.dialog('destroy');
        dm.dialog.remove();
    });
}