export default interface InsertionInterface{
    name: string,
    image: File,
    type: string,
    price: number,
    shipping: number,
    condition: string,
    state: string,
    city: string,
    description: string,
    ajax? : boolean
}