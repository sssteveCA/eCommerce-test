export interface TableEventsInterface{
    form_class: string; //The class of the form submitted
    button_classes: Array<string>; //button classes list when events occur
    operations: Operations; //Orders table backend operations list
}

export interface Operations{
    quantity: number;
    details: number;
    delete: number;
    cart: number;
}