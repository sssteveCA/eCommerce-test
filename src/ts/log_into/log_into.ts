$(()=>{
    $('#show').on('change',(e)=>{
        if($(e.target).is(':checked'))
            $('#password').attr('type','text');
        else
            $('#password').attr('type','password');
    });
});