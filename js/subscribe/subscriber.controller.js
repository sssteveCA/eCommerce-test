var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import DialogMessage from "../dialog/dialogmessage.js";
//Do the HTTP request passing Subscriber object
export default class SubscriberController {
    constructor(subscriber) {
        this._subscriber = subscriber;
        this._errno = 0;
        this._error = null;
        this.subscribeRequest();
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
                let dm, dmData, msgDialog, resJson;
                this.subscribePromise().then(res => {
                    //console.log(res);
                    resJson = JSON.parse(res);
                    dmData = {
                        title: 'Registrazione',
                        message: resJson.msg
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#' + dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
                }).catch(err => {
                    console.warn(err);
                    dmData = {
                        title: 'Registrazione',
                        message: err
                    };
                    dm = new DialogMessage(dmData);
                    msgDialog = $('#' + dm.id);
                    $('div.ui-dialog-buttonpane div.ui-dialog-buttonset button:first-child').on('click', () => {
                        //User press OK button
                        msgDialog.dialog('destroy');
                        msgDialog.remove();
                    });
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
                    confPass: this.subscriber.confPass,
                    ajax: this.subscriber.isAjax
                };
                console.log("subscribePromise  data");
                console.log(data);
                const params = {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    }
                };
                const response = fetch(SubscriberController.SUBSCRIBE_URL, params);
                response.then(r => {
                    resolve(r.text());
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
SubscriberController.ERR_MSG_NOSUBSCRIBEROBJECT = "L'oggetto Subscriber non ?? stato definito";
SubscriberController.ERR_MSG_DATAMISSED = "Una o pi?? propriet?? richieste non esistono";
SubscriberController.SUBSCRIBE_URL = 'funzioni/newAccount.php';
SubscriberController.ERR_MSG_SUBSCRIBEERROR = "Errore durante la richiesta d'iscrizione. Riprovare pi?? tardi e se il problema persiste contattare l'amministratore del sito";
