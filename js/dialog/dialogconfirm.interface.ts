export default interface DialogConfirmInterface{
    id?: string;
    width?: number|string;
    height?: number|string;
    title: string;
    message: string;
    modal?: boolean;
    resizable?: boolean;
    draggable?: boolean;
}