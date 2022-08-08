//jQueryUI confirm dialog with YES/NO buttons
export default class DialogConfirm {
    constructor(data) {
        if (data.hasOwnProperty('id'))
            this._id = data.id;
        else
            this._id = 'dialog';
        if (data.hasOwnProperty('width'))
            this._width = data.width;
        else
            this._width = 'auto';
        if (typeof this._width == 'number')
            this._width = this._width + 'px';
        if (data.hasOwnProperty('height'))
            this._height = data.height;
        else
            this._height = 'auto';
        if (typeof this._height == 'number')
            this._height = this._height + 'px';
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
        this.showDialog();
    }
    get id() { return this._id; }
    get width() { return this._width; }
    get height() { return this._height; }
    get title() { return this._title; }
    get message() { return this._message; }
    get isModal() { return this._modal; }
    get isResizable() { return this._resizable; }
    get isDraggable() { return this._draggable; }
    get dialog() { return this._dialog; }
    get btYes() { return this._btYes; }
    get btNo() { return this._btNo; }
    showDialog() {
        let myParam = {
            title: this._title,
            message: this._message,
            height: this._height,
            width: this._width
        };
        this._dialog = $('<div>');
        this._dialog.attr('id', this._id);
        this._dialog.appendTo($('body'));
        this._dialog.dialog({
            closeOnEscape: false,
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
            open: function () {
                $(this).html(myParam.message);
            },
            close: function () {
            },
            buttons: [
                {
                    text: "SÌ",
                    click: function () {
                    }
                },
                {
                    text: "NO",
                    click: function () {
                    }
                }
            ]
        });
        this._btYes = $('body > div.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-dialog-buttons > div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:first-child');
        this._btNo = $('body > div.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-dialog-buttons > div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:last-child');
    }
}
