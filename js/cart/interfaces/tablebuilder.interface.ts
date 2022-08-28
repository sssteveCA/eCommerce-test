
export interface TableBuilderInterface{
    confirm_params?: TbConfirmParams; //Parameters for cart order pay button
    id_container: string; //id of parent element where table is appended
    cart_data: object;
}

export interface TbConfirmParams{
    sbn_code: string;
    currency: string;
}