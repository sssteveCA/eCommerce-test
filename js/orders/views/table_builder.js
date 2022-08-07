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
        this.setTable();
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
            let columnCart = this.columnCart(); //Add the add Cart column to the table if at least an order is not actually in the cart
            let table = `
<table class="table">`;
            table += this.tableThead(columnCart);
            table += `
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
    <td>${payed}</td>`;
                row += this.formButton(index, order);
                if (columnCart) {
                    row += `<td>`;
                    if (order.cart === false) {
                        row += `
<button type="submit" form="f${index}" class="bCarrello btn btn-secondary" name="bCarrello">CARRELLO</button>
                    `;
                    } //if(order.cart === false){
                    row += `</td>`;
                } //if(columnCart){
                if (payed == 'No') {
                    row += this.payForm(order);
                } //if(payed == 'No'){
                row += `
</tr>
            `;
                table += row;
            }); //this._orders.forEach((order)=>{
            table += `
    </tbody>
</table>
`;
            parent.append(table);
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
        for (let i in this._orders) {
            if (this._orders[i].cart === false) {
                columnCart = true;
                break;
            }
        } //for(let i in this._orders){
        return columnCart;
    }
    //Buttonns and its form part of the table
    formButton(index, order) {
        let formButton = `
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
        `;
        return formButton;
    }
    payForm(order) {
        let payForm = ``;
        var btnPay = false; //true = show the pay button
        payForm += `<td>`;
        if (order.cart === true)
            btnPay = true;
        if (btnPay == true) {
            payForm += `
<form id="pagaForm" method="post" action="conferma.php">
    <input type="hidden" id="idO" name="idO" value="${order.id}">
    <input type="hidden" id="idC" name="idC" value="${order.idc}">
    <input type="hidden" id="idP" name="idP" value="${order.idp}">
    <input type="hidden" id="nP" name="nP" value="${order.quantity}">
    <input type="hidden" id="ord" name="ord" value="1">
    <input type="hidden" id="tot" name="tot" value="${order.total}">
    <button type="submit" class="btn btn-success">PAGA</button>
</form>
            `;
        } //if(btnPaga == true){
        payForm += '</td>';
        return payForm;
    }
    //Table thead HTML
    tableThead(columnCart) {
        let thead = `
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
        if (columnCart === false) {
            thead += `<th scope="col"></th>`;
        }
        thead += `
        </tr>
    </thead>`;
        return thead;
    }
}
