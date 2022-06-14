//Create the Insertion in DB passing the Insertion object
export default class InsertionController {
    constructor(insertion) {
        this._errno = 0;
        this._error = null;
        this._insertion = insertion;
    }
    get insertion() { return this._insertion; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}
//constants
InsertionController.INSERTION_URL = 'funzioni/upload.php';
