import Order from "../models/order.model";

export default interface TableBuilderInterface{
    id_container: string; //id of parent element where table is appended
    orders: Order[];
}