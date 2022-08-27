import TableBuilderInterface from "../interfaces/tablebuilder.interface";

//Print HTML orders table
export default class TableBuilder{
    private _id_container: string; //id of parent element where table is appended
    private _cart_data: object;
    private _table: string = ''; //HTML table
    private _errno: number = 0;
    private _error: string|null = null;

    constructor(data: TableBuilderInterface){
        this._id_container = data.id_container;
        this._cart_data = data.cart_data;
    }

    get cart_data(){return this._cart_data;}
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
    private deleteForm(ido: number, idv: number, i:number): string{
        let df: string = `
<form class="fElim" id="fEl'+i+'" method="post" action="funzioni/cartMan.php">
    <input type="hidden" name="oper" value="3"> 
    <input type="hidden" name="ido" value="${ido}">
    <input type="hidden" name="idv" value="${idv}">
    <input type="submit" class="iElim" id="bElim${i}" value="ELIMINA">
</form>    
        `;
        return df;
    }

    //Form of order details
    private detailsForm(idp: number,i: number): string{
        let df: string = `
<form id="fDett" method="get" action="prodotto.php">
    <input type="hidden" name="id" value="${idp}">
    <input type="submit" class="iDett" id="bDett${i}" value="DETTAGLI">
</form>
        `;
        return df;
    }

    //Single order html row
    private orderRow(order: object, i: number):string{
        let row: string = `
<tr>
<td>${order['nome']}</td>
<td class="timg"><img src="${order['immagine']}"></td>
<td>${order['tipo']}</td>
<td>${order['quantita']}</td>
<td>${order['totale']}</td>
        `;
        row += this.detailsForm(order['idp'],i);
        return row;
    }

    private setTable(): void{
        let parent: JQuery = $('#'+this._id_container);
        parent.html('');
        let table: string = `
        <table >`;
        table += this.tableThead();
        for(let idv in this._cart_data){
            let seller = this._cart_data[idv];
            for(let i in seller){

            }
        }//for(let idv in this._cart_data){
    }

    //Table thead part
    private tableThead(): string{
        let thead: string = `
<th>Nome</th>
<th>Immagine</th>'
<th>Tipo</th>'
<th>Quantità</th>'
<th>Totale</th>'
<th></th><th></th>'
        `;
        return thead;
    }

}