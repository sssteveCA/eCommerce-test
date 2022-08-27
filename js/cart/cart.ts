import GetOrdersInCartInterface from "./interfaces/getordersincart.interface";
import TableBuilderInterface from "./interfaces/tablebuilder.interface";
import GetOrdersInCart from "./requests/getordersincart.js";
import TableBuilder from "./views/tablebuilder.js";

$(()=>{
    let goic_data: GetOrdersInCartInterface = {
        operation: 1
    };
    getCartOrders(goic_data);
    
});

/**
 * Get user orders added to cart
 * @param goic_data 
 */
function getCartOrders(goic_data: GetOrdersInCartInterface): void{
    let goic = new GetOrdersInCart(goic_data);
    goic.getOrdersInCart().then(obj => {
        let errno = goic.errno;
        switch(errno){
            case 0:
                let tb_data: TableBuilderInterface = {
                    id_container: 'carrello',
                    cart_data: obj['carrello']
                };
                table(tb_data);
                break;
            case GetOrdersInCart.ERR_FETCH:
                break;
        }
    });//goic.getOrdersInCart().then(obj => {
}

/**
 * Print User orders in cart table with response data obtained
 * @param tb_data 
 */
function table(tb_data: TableBuilderInterface): void{
    let tab: TableBuilder = new TableBuilder(tb_data);
}