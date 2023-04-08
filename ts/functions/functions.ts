import DialogMessage from "../dialog/dialogmessage";
import DialogMessageInterface from "../dialog/dialogmessage.interface";

/**
 * Show a Dialog with a message and close it on button click
 * @param dmData the Dialog data 
 */
export function showDialogMessage(dmData: DialogMessageInterface): void{
    let dm: DialogMessage = new DialogMessage(dmData);
    dm.btOk.on('click',()=>{
        dm.dialog.dialog('destroy');
        dm.dialog.remove();
    });
}