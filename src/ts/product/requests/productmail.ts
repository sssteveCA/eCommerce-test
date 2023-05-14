import ProductMailInterface from "../interfaces/productmail.interface";

export default class ProductMail{
    private _email: string;
    private _subject: string;
    private _message: string;
    private _errno: number = 0;
    private _error: string|null = null;

    private static SENDMAIL_URL: string = "/funzioni/mail.php";

    public static ERR_REQUEST:number = 1;

    private static ERR_REQUEST_MSG: string = "Errore durante l'invio della mail";

    constructor(data: ProductMailInterface){
        this._email = data.email;
        this._subject = data.subject;
        this._message = data.message;
    }

    get email(){ return this._email; }
    get subject(){ return this._subject; }
    get message(){ return this._message; }
    get errno(){ return this._errno; }
    get error(){ 
        switch(this._errno){
            case ProductMail.ERR_REQUEST:
                this._error = ProductMail.ERR_REQUEST_MSG;
                break;
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    public async sendMail(): Promise<object>{
        this._errno = 0;
        let response: object = {};
        try{
            await this.sendMailPromise().then(res => {
                //console.log(res);
                response = JSON.parse(res);
            }).catch(err => {
                console.warn(err);
                throw err;
            });
        }catch(e){
            this._errno = ProductMail.ERR_REQUEST;
            response = { msg: this.error };
        }
        return response;
    }

    private async sendMailPromise(): Promise<string>{
        return await new Promise<string>((resolve, reject)=>{
            fetch(ProductMail.SENDMAIL_URL,{
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `ajax=1&oper=3&emailTo=${this._email}&pOggetto=${this._subject}&pMessaggio=${this._message}`
            }).then(res => {
                resolve(res.text());
            }).catch(err => {
                reject(err);
            })
        });
    }
}