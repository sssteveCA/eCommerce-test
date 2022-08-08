export default interface EditQuantityInterface{
    id_order: number; //Order to be updated
    operation: number; //command to sent at backend to edit order quantity
    quantity: number; //new order quantity
}