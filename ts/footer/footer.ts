
$(()=>{
    let footer: JQuery = $('.footer');
    if(footer.length){
        footerPosition(footer);
        $(window).on('resize', ()=>{
            footerPosition(footer);
        });
    }
});

/**
 * Put bottom footer corner at bottom of viewport if content isn't high enough
 * @param footerEl 
 */
function footerPosition(footerEl: JQuery): void{
    let wHeight: number = $(window).height() as number;
    let bHeight: number = $('body').height() as number;
    let footerH: number = footerEl.height() as number;
    /* console.log("wWeight => "+wHeight);
    console.log("bHeight => "+bHeight);
    console.log("footerH => "+footerH); */
    if(bHeight < wHeight - footerH){
        footerEl.css({
            position: 'fixed', bottom: '0px'
        });
    }
    else{
        footerEl.css({ position: 'static' });
    }
}

