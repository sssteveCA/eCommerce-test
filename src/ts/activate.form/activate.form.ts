
$(()=>{
    let activate: JQuery<HTMLButtonElement> = $('#activate');
    if(activate.length){
        let divMessage: JQuery<HTMLDivElement> = $('#error-message');
        let codAutInput: JQuery<HTMLInputElement> = $('#activate');
        if(codAutInput.length && divMessage.length){
            divMessage.html('');
            activate.on('click', ()=> {
                let codAut: string = codAutInput.val() as string;
                if(codAut != ''){
                    window.location.href = '/activate'+codAut;
                }
                else divMessage.html('Inserisci il codice di attivazione per continuare');
            })
        }
    }//if(activate.length){
});