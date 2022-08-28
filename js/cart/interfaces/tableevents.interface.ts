export interface TableEventsInterface{
    confirm_params?: TeConfirmParams; //Parameters for cart order pay button
    button_classes: ButtonClasses; //Register events in specified button classes
    form_classes: FormClasses; //Form classes for every operation
    operations: Operations; //Orders table backend operations list
}

export interface Operations{
    delete: number;
}

export interface FormClasses{
    delete: string;
}

export interface ButtonClasses{
    confirm: string;
}

export interface TeConfirmParams{
    sbn_code: string;
    currency: string;
}