//Jquery dialog object
export default class DialogMessage {
    constructor(data) {
        if (data.hasOwnProperty('id'))
            this._id = data.id;
        else
            this._id = 'dialog';
        if (data.hasOwnProperty('width'))
            this._width = data.width;
        else
            this._width = 'auto';
        if (data.hasOwnProperty('height'))
            this._height = data.height;
        else
            this._height = 400;
        this._title = data.title;
        this._message = data.message;
        if (data.hasOwnProperty('modal'))
            this._modal = data.modal;
        else
            this._modal = true;
        if (data.hasOwnProperty('resizable'))
            this._resizable = data.resizable;
        else
            this._resizable = false;
        if (data.hasOwnProperty('draggable'))
            this._draggable = data.draggable;
        else
            this._draggable = false;
    }
    get id() { return this._id; }
    get width() { return this._width; }
    get height() { return this._height; }
    get title() { return this._title; }
    get message() { return this._message; }
    get isModal() { return this._modal; }
    get isResizable() { return this._resizable; }
    get isDraggable() { return this._draggable; }
}
