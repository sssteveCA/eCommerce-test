import TableBuilderInterface from "../interfaces/table_builder.interface";
import Order from "../models/order.model";


//Print HTML orders table
export default class TableBuilder{
    private _id_container: string;
    private _done: boolean;
    private _orders: Order[];
    private _msg: string;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(data: TableBuilderInterface){
        this._id_container = data.id_container;
        this._done = data.done;
        this._orders = data.orders;
        this._msg = data.msg;
    }

    get done(){return this._done;}
    get orders(){return this._orders;}
    get orders_count(){return this._orders.length;}
    get msg(){return this._msg;}
    get table(){return this._table;}
    get errno(){return this._errno;}
    get error(){
        switch(this._errno){
            default:
                this._error = null;
                break;
        }
        return this._error;
    }

    private setTable(): void{
        let parent: JQuery = $('#'+this._id_container);
        parent.html('');
        if(this._done == true && this.orders_count > 0){
            let columnCart: boolean = this.columnCart(); //Add the add Cart column to the table if at least an order is not actually in the cart
            let table: string = `
<table class="table">
    <thead>
        <tr>
            <th scope="col">Id ordine</th>
            <th scope="col">Id prodotto</th>
            <th scope="col">Id venditore</th>
            <th scope="col">Data ordine</th>
            <th scope="col">Quantità</th>
            <th scope="col">Prezzo totale</th>
            <th scope="col">Ordine pagato</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>`;
            if(columnCart === false){
                table += `<th scope="col"></th>`;
            }
                table += `
        </tr>
    </thead>
    <tbody>
            `;
        this._orders.forEach((order,index)=>{
            let payed:string = (order.payed === true) ? "Sì" : "No";
            let row: string = `
<tr>
    <td>${order.id}</td>        
    <td>${order.idp}</td>        
    <td>${order.idv}</td>        
    <td>${order.date}</td>        
    <td class="tQuantita">
        <input type="number" id="q${index}" class="iQuantita" name="quantita" form="f${index}" value="${order.quantity}">
        <button type="submit" class="btn btn-primary">MODIFICA</button>
    </td>        
    <td>${order.total}€</td>        
    <td>${payed}</td>    
    <form id="f${index}" class="formOrder" method="get" action="orderMan.php"></form>   
    <td>
        <input type="hidden" form="f${index}" class="idOrd" name="idOrd" value="${order.id}">
    </td>        
    <td>
            <button type="submit" form="f${index}" class="bDettagli btn btn-secondary" name="bDettagli">DETTAGLI</button>
    </td>        
    <td>
        <button type="submit" form="f${index}" class="bElimina btn btn-danger" name="bElimina">ELIMINA</button>
    </td> `;
            if(columnCart){
                row += `<td>`;
                if(order.cart === false){
                    row += `
                        <button type="submit" form="f${index}" class="bCarrello btn btn-secondary" name="bCarrello">CARRELLO</button>
                    `; 
                }//if(order.cart === false){
                row += `</td>`;   
            }//if(columnCart){
            row += `
</tr>
            `;
            table += row;
        });//this._orders.forEach((order)=>{
        table += `
    </tbody>
</table>
`;

        }//if(this._done == true && this.orders_count > 0){
        else{
            //No orders found
            var pr = $('<p>');
            pr.html('Nessun ordine effettuato');
            pr.css({
                'text-align' : 'center',
                'font-size' : '22px',
                'font-weight' : 'bold'
            });
            parent.append(pr);
        }
    }

    private columnCart(): boolean{
        let columnCart: boolean = false;
        for(let i in this._orders){
            if(this._orders[i].cart === false){
                columnCart = true;
                break;
            }
        }//for(let i in this._orders){
        return columnCart;
    }

    private payForm(): string{
        let payForm: string = ``;
        return payForm;
    }
}