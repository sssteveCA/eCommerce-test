var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
//Do the HTTP request passing Subscriber object
export default class SubscriberController {
    constructor(subscriber) {
        this._subscriber = subscriber;
        this._errno = 0;
        this._error = null;
    }
    get subscriber() { return this._subscriber; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            case SubscriberController.ERR_NOSUBSCRIBEROBJECT:
                this._error = SubscriberController.ERR_MSG_NOSUBSCRIBEROBJECT;
                break;
            case SubscriberController.ERR_DATAMISSED:
                this._error = SubscriberController.ERR_MSG_DATAMISSED;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    //check if Subscriber object has required properties
    validateSubscriber() {
        let ok = false;
        if (this.subscriber.name && this.subscriber.surname && this.subscriber.birth && this.subscriber.sex && this.subscriber.address && this.subscriber.number && this.subscriber.city && this.subscriber.zip && this.subscriber.username && this.subscriber.email && this.subscriber.password && typeof (this.subscriber.isAjax) != "undefined") {
            ok = true;
        }
        return ok;
    }
    //Do the subscribe request
    subscribeRequest() {
        if (this.subscriber != null) {
            if (this.validateSubscriber()) {
                this.subscribePromise().then(res => {
                    console.log(res);
                }).catch(err => {
                    console.warn(err);
                });
            } //if(this.validateSubscriber()){
            else
                this._errno = SubscriberController.ERR_DATAMISSED;
        } //if(this.subscriber != null){
        else
            this._errno = SubscriberController.ERR_NOSUBSCRIBEROBJECT;
    }
    subscribePromise() {
        return __awaiter(this, void 0, void 0, function* () {
            return yield new Promise((resolve, reject) => {
                const data = {
                    name: this.subscriber.name,
                    surname: this.subscriber.surname,
                    birth: this.subscriber.birth,
                    sex: this.subscriber.sex,
                    address: this.subscriber.address,
                    number: this.subscriber.number,
                    city: this.subscriber.city,
                    zip: this.subscriber.zip,
                    paypalMail: this.subscriber.paypalMail,
                    clientId: this.subscriber.clientId,
                    email: this.subscriber.email,
                    username: this.subscriber.username,
                    password: this.subscriber.password,
                    ajax: this.subscriber.isAjax
                };
                const params = {
                    method: 'POST',
                    body: '',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };
                const response = fetch(SubscriberController.SUBSCRIBE_URL, params);
                response.then(r => {
                    resolve(r.json());
                }).catch(err => {
                    console.warn(err);
                    reject(SubscriberController.ERR_MSG_SUBSCRIBEERROR);
                });
            });
        });
    }
}
SubscriberController.ERR_NOSUBSCRIBEROBJECT = 1; //Subscriber object is null
SubscriberController.ERR_DATAMISSED = 2; //One or more properties of Subscriber object missed
SubscriberController.ERR_MSG_NOSUBSCRIBEROBJECT = "L'oggetto Subscriber non è stato definito";
SubscriberController.ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
SubscriberController.SUBSCRIBE_URL = 'funzioni/newAccount.php';
SubscriberController.ERR_MSG_SUBSCRIBEERROR = "Errore durante la richiesta d'iscrizione. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";
