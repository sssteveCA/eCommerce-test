import Insertion from "./insertion.model";
import InsertionController from "./insertion.controller";
import InsertionInterface from "./data.interface";

$(function(){
    $('#fInsertion').on('submit',(ev)=>{
        ev.preventDefault();
        let image = <HTMLInputElement>$('#image')[0];
        if(image != null){
            //Element exists
            let imageFileList : FileList|null = image.files;
            if(imageFileList){
                //File list from input type file exists
                let imageFile : File|null = imageFileList.item(0);
                var data: InsertionInterface = {
                    name: $('#name').val() as string,
                    image: imageFile as File,
                    type: $('#type').filter(':selected').val() as string,
                    price: $('#price').val() as number,
                    shipping: $('#shipping').val() as number,
                    condition: $('input[name=condition]:checked').val() as string,
                    state: $('#state').val() as string,
                    city: $('#city').val() as string,
                    description: $('#description').val() as string,
                    ajax: true
                };
                console.log(data);
            }//if(imageFileList){
        }//if(image != null){  
    });
});