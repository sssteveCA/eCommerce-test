//Do the HTTP request passing Subscriber object
export default class SubscriberController {
    constructor(subscriber) {
        this._subscriber = subscriber;
        this._errno = 0;
        this._error = null;
    }
    get subscriber() { return this._subscriber; }
    get errno() { return this._errno; }
    get error() { return this._error; }
    //check if Subscriber object has required properties
    validateSubscriber() {
        let ok = false;
        return ok;
    }
    //Do the subscribe request
    subscribeRequest() {
    }
    subscribePromise() {
        return new Promise((resolve, reject) => {
        });
    }
}
