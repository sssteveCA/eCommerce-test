
$(()=>{
    cbPrice();
    cbOldDate();
    cbRecentDate();
    cbState();
    cbCity();
});

function cbPrice(): void{
    $('#cPrezzo').on('change',(e)=>{
        let thisEl = $(e.target);
        if(thisEl.is(':checked')){
            $('#minP').prop('disabled', false);
            $('#maxP').prop('disabled', false);
        }
        else{
            $('#minP').prop('disabled',true);
            $('#maxP').prop('disabled',true);
        }
    });
}

function cbOldDate(): void{
    $('#dataI').on('change', (e)=>{
        let thisEl = $(e.target);
        if(thisEl.is(':checked')) $('#oDate').prop('disabled',false);
        else $('#oDate').prop('disabled',true);
    });
}

function cbRecentDate(): void{
    $('#dataF').on('change', (e)=>{
        let thisEl = $(e.target);
        if(thisEl.is(':checked')) $('#rDate').prop('disabled',false);
        else $('#rDate').prop('disabled',true);
    });
}

function cbState(): void{
    $('#cStato').on('change', (e)=>{
        let thisEl = $(e.target);
        if(thisEl.is(':checked')) $('#stato').prop('disabled',false);
        else $('#stato').prop('disabled',true);
    });
}

function cbCity(): void{
    $('#cCitta').on('change', (e)=>{
        let thisEl = $(e.target);
        if(thisEl.is(':checked')) $('#citta').prop('disabled',false);
        else $('#citta').prop('disabled',true);
    });
}