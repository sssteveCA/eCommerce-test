import Insertion from "./insertion.model.js";
import InsertionController from "./insertion.controller.js";
$(function () {
    $('#fInsertion').on('submit', (ev) => {
        ev.preventDefault();
        let image = $('#image')[0];
        if (image != null) {
            //Element exists
            let imageFileList = image.files;
            if (imageFileList) {
                //File list from input type file exists
                let imageFile = imageFileList.item(0);
                var data = {
                    name: $('#name').val(),
                    image: imageFile,
                    type: $('#type').val(),
                    price: $('#price').val(),
                    shipping: $('#shipping').val(),
                    condition: $('input[name=condition]:checked').val(),
                    state: $('#state').val(),
                    city: $('#city').val(),
                    description: $('#description').val(),
                    ajax: true
                };
                console.log(data);
                let insertion = new Insertion(data);
                let insertionController = new InsertionController(insertion);
            } //if(imageFileList){
        } //if(image != null){  
    });
});
