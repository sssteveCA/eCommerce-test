import ProductMailInterface from "./interfaces/productmail.interface";
import ProductMailController from "./requests/productmail.controller";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import DialogMessage from "../dialog/dialogmessage";

$(()=>{
    $('#formMail').on('submit',(e)=>{
       e.preventDefault();
       let pmData: ProductMailInterface = {
        email: $('#emailTo').val() as string,
        subject: $('#oggetto').val() as string,
        message: $('#messaggio').val() as string
       };
       let pmc: ProductMailController = new ProductMailController(pmData);
       pmc.sendMail().then(obj => {
        let dmData: DialogMessageInterface = {
            title: 'Mail al venditore', message: obj["msg"]
        };
        let dm: DialogMessage = new DialogMessage(dmData);
        dm.btOk.on('click',()=>{
            dm.dialog.dialog('destroy');
            dm.dialog.remove();
        });
       });
    });
    $('#elimina').on('submit',(e)=>{
        e.preventDefault();
    });
});