import DialogMessage from "../dialog/dialogmessage";
import DialogMessageInterface from "../dialog/dialogmessage.interface";

/**
 * Print an error message in a <p> element
 * @param id_container the id of the parent element
 * @param message the message to show inside the <p> tag
 */
export function error(id_container: string, message: string): void{
    let p: JQuery<HTMLParagraphElement> = $('<p>')
    p.addClass('error');
    p.html(message)
    $('#'+id_container).append(p)
}

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