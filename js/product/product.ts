import ProductMailInterface from "./interfaces/productmail.interface";
import ProductMailController from "./requests/productmail.controller";
import DialogMessageInterface from "../dialog/dialogmessage.interface";
import DialogMessage from "../dialog/dialogmessage";
import DeleteProductInterface from "./interfaces/deleteproduct.interface";
import DeleteProductController from "./requests/deleteproduct.controller";

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
       });//pmc.sendMail().then(obj => {
    });//$('#formMail').on('submit',(e)=>{
    $('#elimina').on('submit',(e)=>{
        e.preventDefault();
        let dpData: DeleteProductInterface = {
            productId: $('#idp').val() as string
        }
        let dpc: DeleteProductController = new DeleteProductController(dpData);
        dpc.deleteProduct().then(obj => {
            let dmData: DialogMessageInterface = {
                title: 'Inserzione prodotto', message: obj["msg"]
            };
            let dm: DialogMessage = new DialogMessage(dmData);
            dm.btOk.on('click',()=>{
                dm.dialog.dialog('destroy');
                dm.dialog.remove();
                if(obj["done"] == true){
                    window.location.href = 'inserzioni.php';
                }
            });
        });//dpc.deleteProduct().then(obj => {
    });//$('#elimina').on('submit',(e)=>{
});