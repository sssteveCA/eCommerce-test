import Subscriber from "./subscriber.model";

//Do the HTTP request passing Subscriber object
export default class SubscriberController{
    private _subscriber: Subscriber;
    private _errno: number;
    private _error: string|null;

    constructor(subscriber: Subscriber){
        this._subscriber = subscriber;
        this._errno = 0;
        this._error = null;
    }

    get subscriber(){return this._subscriber;}
    get errno(){return this._errno;}
    get error(){return this._error;}

    //check if Subscriber object has required properties
    private validateSubscriber(): boolean{
        let ok = false;
        return ok;
    }

    //Do the subscribe request
    private subscribeRequest(): void{

    }

    private subscribePromise(): Promise<string>{
        return new Promise((resolve, reject) => {

        });
    }
}