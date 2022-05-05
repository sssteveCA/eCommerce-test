
//Format of javascript to pass for user registration
export default interface SubsciberInterface{
    name: string;
    surname: string;
    birth: Date|string;
    sex: string;
    address: string;
    number: number;
    city: string;
    zip: string;
    username: string;
    paypalMail?: string;
    clientId?: string;
    email: string;
    password: string;
    confPass: string;
    ajax?: boolean;
}