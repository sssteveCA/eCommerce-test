import Subscriber from "./subscriber.model";

//Do the HTTP request passing Subscriber object
export default class SubscriberController{

    public static ERR_NOSUBSCRIBEROBJECT = 1; //Subscriber object is null
    public static ERR_DATAMISSED = 2; //One or more properties of Subscriber object missed

    private static ERR_MSG_NOSUBSCRIBEROBJECT = "L'oggetto Subscriber non è stato definito";
    private static ERR_MSG_DATAMISSED = "Una o più proprietà richieste non esistono";

    private static SUBSCRIBE_URL = 'funzioni/newAccount.php';

    private static ERR_MSG_SUBSCRIBEERROR = "Errore durante la richiesta d'iscrizione. Riprovare più tardi e se il problema persiste contattare l'amministratore del sito";

    private _subscriber: Subscriber;
    private _errno: number;
    private _error: string|null;

    constructor(subscriber: Subscriber){
        this._subscriber = subscriber;
        this._errno = 0;
        this._error = null;
        this.subscribeRequest();
    }

    get subscriber(){return this._subscriber;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
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
    private validateSubscriber(): boolean{
        let ok = false;
        if(this.subscriber.name && this.subscriber.surname && this.subscriber.birth && this.subscriber.sex && this.subscriber.address && this.subscriber.number && this.subscriber.city && this.subscriber.zip && this.subscriber.username && this.subscriber.email && this.subscriber.password && typeof(this.subscriber.isAjax) != "undefined"){
            ok = true;
        }
        return ok;
    }

    //Do the subscribe request
    private subscribeRequest(): void{
        if(this.subscriber != null){
            if(this.validateSubscriber()){
                this.subscribePromise().then(res => {
                    console.log(res);
                }).catch(err => {
                    console.warn(err);
                });
            }//if(this.validateSubscriber()){
            else this._errno = SubscriberController.ERR_DATAMISSED;
        }//if(this.subscriber != null){
        else this._errno = SubscriberController.ERR_NOSUBSCRIBEROBJECT;

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
            const response = fetch(SubscriberController.SUBSCRIBE_URL,params);
            response.then(r => {
                resolve(r.json());
            }).catch(err => {
                console.warn(err);
                reject(SubscriberController.ERR_MSG_SUBSCRIBEERROR);
            });

        });
    }
}