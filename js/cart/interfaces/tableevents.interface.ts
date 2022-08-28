export interface TableEventsInterface{
    form_classes: FormClasses; //Form classes for every operation
    operations: Operations; //Orders table backend operations list
}

export interface Operations{
    delete: number;
}

export interface FormClasses{
    delete: string;
}