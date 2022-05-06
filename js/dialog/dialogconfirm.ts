import DialogConfirmInterface from "./dialogconfirm.interface";

//jQueryUI confirm dialog with YES/NO buttons
export default class DialogConfirm{
    private _id: string;
    private _width: number|string;
    private _height: number|string;
    private _title: string;
    private _message: string;
    private _modal: boolean;
    private _resizable: boolean;
    private _draggable: boolean;

    constructor(data: DialogConfirmInterface){
        if(data.hasOwnProperty('id'))this._id = data.id as string;
        else this._id = 'dialog';
        if(data.hasOwnProperty('width'))this._width = data.width as number|string;
        else this._width = 'auto';
        if(typeof this._width == 'number')this._width = this._width+'px';
        if(data.hasOwnProperty('height'))this._height = data.height as number|string;
        else this._height = 'auto';
        if(typeof this._height == 'number')this._height = this._height+'px';
        this._title = data.title;
        this._message = data.message;
        if(data.hasOwnProperty('modal'))this._modal = data.modal as boolean;
        else this._modal = true;
        if(data.hasOwnProperty('resizable'))this._resizable = data.resizable as boolean;
        else this._resizable = false;
        if(data.hasOwnProperty('draggable'))this._draggable = data.draggable as boolean;
        else this._draggable = false;
        this.showDialog();
    }

    get id(){return this._id;}
    get width(){return this._width;}
    get height(){return this._height;}
    get title(){return this._title;}
    get message(){return this._message;}
    get isModal(){return this._modal;}
    get isResizable(){return this._resizable;}
    get isDraggable(){return this._draggable;}

    private showDialog(): void{
        let myParam = {
            title: this._title,
            message: this._message,
            height: this._height,
            width: this._width
        };
        $('<div id="'+this._id+'">').dialog({
            resizable: this._resizable,
            draggable: this._draggable,
            position: {
                my: 'center center',
                at: 'center center',
                of: window
            },
            height: myParam.height,
            width: myParam.width,
            modal: this._modal,
            title: myParam.title,
            open: function(){
                $(this).html(myParam.message);
            },
            buttons: [
                {
                    text: "SÌ",
                    click: function(){
                        $(this).dialog('destroy');
                    }
                },
                {
                    text: "NO",
                    click: function(){
                        $(this).dialog('destroy');
                    }
                }
            ]
        });
    }
}
