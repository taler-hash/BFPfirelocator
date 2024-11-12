export interface FilterTypes {
    id?: number,
    page: number,
    sortBy: string | 'id',
    sortType: 'asc'|'desc',
    rows: number,
    searchString?: string,
}