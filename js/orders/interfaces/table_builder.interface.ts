import Order from "../models/order.model";

export default interface TableBuilderInterface{
    done: boolean;
    orders: Order[];
    msg: string;
}