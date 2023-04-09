export default interface DeleteCartOrderInterface{
    order_id: number; //Order id to remove from cart
    operation: number; //command to sent at backend to get orders in backend
}