import Subscriber from "./subscriber.model";

//Do the HTTP request passing Subscriber object
export default class SubscriberRequest{

    public static ERR_NOSUBSCRIBEROBJECT = 1; //Subscriber object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Subscriber object missed
    public static ERR_REQUEST = 3;

    private static ERR_MSG_NOSUBSCRIBEROBJECT = "L'oggetto Subscriber non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";
    private static ERR_MSG_REQUEST = "Errore durante l'esecuzione della richeista";

    private static SUBSCRIBE_URL = 'funzioni/newAccount.php';

    private static ERR_MSG_SUBSCRIBEERROR = "Errore durante la richiesta d'iscrizione. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";

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
    get error(){
        switch(this._errno){
            case SubscriberRequest.ERR_NOSUBSCRIBEROBJECT:
                this._error = SubscriberRequest.ERR_MSG_NOSUBSCRIBEROBJECT;
                break;
            case SubscriberRequest.ERR_DATAMISSED:
                this._error = SubscriberRequest.ERR_MSG_DATAMISSED;
                break;
            case SubscriberRequest.ERR_REQUEST:
                this._error = SubscriberRequest.ERR_MSG_REQUEST;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    //check if Subscriber object has required properties
    private validateSubscriber(): boolean{
        let ok = false;
        if(this.subscriber.name && this.subscriber.surname && this.subscriber.birth && this.subscriber.sex && this.subscriber.address && this.subscriber.number && this.subscriber.city && this.subscriber.zip && this.subscriber.username && this.subscriber.email && this.subscriber.password && typeof(this.subscriber.isAjax) != "undefined"){
            ok = true;
        }
        return ok;
    }

    public async subscribeRequest(): Promise<object>{
        this._errno = 0;
        let response: object = {};
        if(this._subscriber != null){
            if(this.validateSubscriber()){
                try{
                    await this.subscribePromise().then(res => {
                        //console.log(res)
                        response = JSON.parse(res);
                    }).catch(err => {
                        throw err;
                    });
                }catch(e){
                    this._errno = SubscriberRequest.ERR_REQUEST;
                    response = { msg: SubscriberRequest.ERR_MSG_SUBSCRIBEERROR };
                }
            }//if(this.validateSubscriber()){
            else{
                this._errno = SubscriberRequest.ERR_DATAMISSED;
                response = { msg: SubscriberRequest.ERR_MSG_SUBSCRIBEERROR };
            }
        }//if(this._subscriber != null){
        else{
            this._errno = SubscriberRequest.ERR_NOSUBSCRIBEROBJECT;
            response = { msg: SubscriberRequest.ERR_MSG_SUBSCRIBEERROR };
        }
        return response;
    }

    private async subscribePromise(): Promise<any>{
        return await new Promise((resolve, reject) => {
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
            /* console.log("subscribePromise  data");
            console.log(data); */
            const params = {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            };
            const response = fetch(SubscriberRequest.SUBSCRIBE_URL,params);
            response.then(r => {
                resolve(r.text());
            }).catch(err => {
                console.warn(err);
                reject(SubscriberRequest.ERR_MSG_SUBSCRIBEERROR);
            });

        });
    }
}