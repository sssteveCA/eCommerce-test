import TableBuilderInterface from "../interfaces/tablebuilder.interface";

//Print HTML orders table
export default class TableBuilder{
    private _id_container: string; //id of parent element where table is appended
    private _cart_data: object;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    private static N_COLUMS: number = 7; //columns number of the table

    constructor(data: TableBuilderInterface){
        this._id_container = data.id_container;
        this._cart_data = data.cart_data;
        this.setTable();
    }

    get cart_data(){return this._cart_data;}
    get cart_data_length():number{
        return Object.keys(this._cart_data).length;
    }
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

    //Form of cart order delete
    private deleteForm(ido: number, idv: number, i:string): string{
        let df: string = `
<form class="fElim" id="fEl${i}" method="post" action="funzioni/cartMan.php">
    <input type="hidden" name="oper" value="3"> 
    <input type="hidden" name="ido" value="${ido}">
    <input type="hidden" name="idv" value="${idv}">
    <input type="submit" class="iElim" id="bElim${i}" value="ELIMINA">
</form>    
        `;
        return df;
    }

    //Form of order details
    private detailsForm(idp: number,i: string): string{
        let df: string = `
<form id="fDett" method="get" action="prodotto.php">
    <input type="hidden" name="id" value="${idp}">
    <input type="submit" class="iDett" id="bDett${i}" value="DETTAGLI">
</form>
        `;
        return df;
    }

    //Single order html row
    private orderRow(order: object, i: string):string{
        console.log("order");
        console.log(order);
        let row: string = `
<tr>
<td>${order[i]['nome']}</td>
<td class="timg"><img src="${order[i]['immagine']}"></td>
<td>${order[i]['tipo']}</td>
<td>${order[i]['quantita']}</td>
<td>${order[i]['totale']}</td>
        `;
        let details_form: string = this.detailsForm(order[i]['idp'],i);
        let delete_form: string = this.deleteForm(order[i]['ido'],order[i]['idv'],i);
        row += `<td>${details_form}</td>`;
        row += `<td>${delete_form}</td>`;
        row += `</tr>`;
        return row;
    }

    private payButton(idv: string): string{
        let colspan = TableBuilder.N_COLUMS;
        let payForm: string = `
<tr>
<td colspan="${colspan}">
<div id="divCarrello${idv}" style="padding:10px; display:flex; justify-content:center; align-items:center;">
    <div id="paypalArea${idv}" class="paypalArea">

    </div>
    <div id="confirm${idv}" class="confirm">
        <button id="confirmButton${idv}" class="confirmButton">PAGA ORDINI</button>
    </div>
</div> 
</td>
</tr>     
        `;
        return payForm;
    }

    private setTable(): void{
        let parent: JQuery = $('#'+this._id_container);
        parent.html('');
        let table: string = ``;
        if(this.cart_data_length > 0){
            //If user has at least one cart in the cart
            table = `
            <table>`;
            table += this.tableThead();
            table += `<tbody>`;
            for(let idv in this._cart_data){
                console.log("idv in cart data");
                let seller = this._cart_data[idv] as object;
                //console.log(seller);
                for(let i in seller){
                    console.log("i in seller");
                    table += this.orderRow(seller,i);
                }
                table += this.payButton(idv);
            }//for(let idv in this._cart_data){
            table += `</tbody></table>`;
        }//if(this.cart_data_length > 0){
        else{
           table = `
<p style="text-align:center; font-size: 22px; font-weight: bold;">
Nessun ordine effettuato
</p>           
`; 
        }
        parent.append(table);
        this._table = table;
    }

    //Table thead part
    private tableThead(): string{
        let thead: string = `
<thead>
<tr>
<th>Nome</th>
<th>Immagine</th>'
<th>Tipo</th>'
<th>Quantità</th>'
<th>Totale</th>'
<th></th><th></th>
</tr>
</thead>'
        `;
        return thead;
    }

}