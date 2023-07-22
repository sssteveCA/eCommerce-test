
$(()=>{
    let activate: JQuery<HTMLButtonElement> = $('#attiva');
    if(activate.length){
        let divMessage: JQuery<HTMLDivElement> = $('#error-message');
        let actCodeInput: JQuery<HTMLInputElement> = $('#activate');
        if(actCodeInput.length && divMessage.length){
            divMessage.html('');
            activate.on('click', ()=> {
                let actCode: string = actCodeInput.val() as string;
                if(actCode != ''){
                    window.location.href = '/activate'+actCode;
                }
                else divMessage.html('Inserisci il codice di attivazione per continuare');
            })
        }
    }//if(activate.length){
});