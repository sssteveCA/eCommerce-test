import Insertion from "./insertion.model.js";
import InsertionController from "./insertion.controller.js";
import InsertionInterface from "./data.interface.js";

$(function(){
    $('#fInsertion').on('submit',(ev)=>{
        ev.preventDefault();
        let image = <HTMLInputElement>$('#image')[0];
        if(image != null){
            //Element exists
            let imageFileList = image.files as FileList;
            if(imageFileList){
                //File list from input type file exists
                let imageFile = imageFileList.item(0) as File;
                var data: InsertionInterface = {
                    name: $('#name').val() as string,
                    image: imageFile,
                    type: $('#type').val() as string,
                    price: $('#price').val() as number,
                    shipping: $('#shipping').val() as number,
                    condition: $('input[name=condition]:checked').val() as string,
                    state: $('#state').val() as string,
                    city: $('#city').val() as string,
                    description: $('#description').val() as string,
                    ajax: true
                };
                console.log(data);
                let insertion = new Insertion(data);
                let insertionController = new InsertionController(insertion);
            }//if(imageFileList){
        }//if(image != null){  
    });
});