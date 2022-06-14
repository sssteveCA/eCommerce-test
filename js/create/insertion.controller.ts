import Insertion from "./insertion.model";
import DialogMessage from "../dialog/dialogmessage";

//Create the Insertion in DB passing the Insertion object
export default class InsertionController{

    //constants
    private static INSERTION_URL = 'funzioni/upload.php';

    //errors

    //properties
    private _insertion: Insertion;
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(insertion: Insertion){
        this._insertion = insertion;
    }

    get insertion(){return this._insertion;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
}