import {TbConfirmParams, TableBuilderInterface} from "../interfaces/tablebuilder.interface";

//Print HTML orders table
export default class TableBuilder{
    private _confirm_params: TbConfirmParams;
    private _id_container: string; //id of parent element where table is appended
    private _cart_data: object;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    private static N_COLUMS: number = 7; //columns number of the table

    constructor(data: TableBuilderInterface){
        this._id_container = data.id_container;
        this._cart_data = data.cart_data;
        /* console.log("tableevents.ts cart data");
        console.log(this._cart_data); */
        this._confirm_params = data.confirm_params;
        /* console.log("tableevents.ts confirm params");
        console.log(this._confirm_params); */
        this.setTable();
    }

    get confirm_params(){return this._confirm_params;}
    get cart_data(){return this._cart_data;}
    get cart_data_length():number{
        if(this._cart_data)
            return Object.keys(this._cart_data).length;
        else return 0;
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
    <button type="submit" class="btn btn-danger iElim" id="bElim${i}">ELIMINA</button>
</form>    
        `;
        return df;
    }

    //Form of order details
    private detailsForm(idp: number,i: string): string{
        let df: string = `
<a id="bDett${i}" class="btn btn-primary iDett" href="/product/${idp}" role="button">DETTAGLI</a>
        `;
        return df;
    }

    //Single order html row
    private orderRow(order: object, i: string):string{
        let row: string = `
<tr>
<td>${order[i]['nome']}</td>
<td class="timg"><img src="${order[i]['immagine']}"></td>
<td>${order[i]['tipo']}</td>
<td>${order[i]['quantita']}</td>
<td>${order[i]['totale']}€</td>
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
<div id="divCarrello${idv}" class="divCart">
    <div id="paypalArea${idv}" class="paypalArea">

    </div>
    <div id="confirm${idv}" class="confirm">
        <button id="confirmButton${idv}" class="btn btn-success btn-lg confirmButton">PAGA ORDINI</button>
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
            <table class="table table-hover">`;
            table += this.tableThead();
            table += `<tbody>`;
            for(let idv in this._cart_data){
                let seller = this._cart_data[idv] as object;
                //console.log(seller);
                for(let i in seller){
                    table += this.orderRow(seller,i);
                }
                table += this.payButton(idv);
            }//for(let idv in this._cart_data){
            table += `</tbody></table>`;
        }//if(this.cart_data_length > 0){
        else{
           table = `
<p id="null">
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
<thead class="table-light">
<tr>
<th scope="col">Nome</th>
<th>Immagine</th>
<th scope="col">Tipo</th>
<th scope="col">Quantità</th>
<th scope="col">Totale</th>
<th scope="col"></th><th></th>
</tr>
</thead>
        `;
        return thead;
    }

}