//Print HTML orders table
export default class TableBuilder {
    constructor(data) {
        this._table = ''; //HTML table
        this._errno = 0;
        this._error = null;
        this._id_container = data.id_container;
        this._done = data.done;
        this._orders = data.orders;
        this._msg = data.msg;
    }
    get done() { return this._done; }
    get orders() { return this._orders; }
    get orders_count() { return this._orders.length; }
    get msg() { return this._msg; }
    get table() { return this._table; }
    get errno() { return this._errno; }
    get error() {
        switch (this._errno) {
            default:
                this._error = null;
                break;
        }
        return this._error;
    }
    setTable() {
        let parent = $('#' + this._id_container);
        parent.html('');
        if (this._done == true && this.orders_count > 0) {
            let table = `
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
            table += `
        </tr>
    </thead>
    <tbody>
            `;
            this._orders.forEach((order, index) => {
                let payed = (order.payed === true) ? "Sì" : "No";
                let row = `
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
    </td>        
</tr>
            `;
            }); //this._orders.forEach((order)=>{
            table += `
    </tbody>
</table>
`;
        } //if(this._done == true && this.orders_count > 0){
        else {
            //No orders found
            var pr = $('<p>');
            pr.html('Nessun ordine effettuato');
            pr.css({
                'text-align': 'center',
                'font-size': '22px',
                'font-weight': 'bold'
            });
            parent.append(pr);
        }
    }
    columnCart() {
        let columnCart = false;
        return columnCart;
    }
}
