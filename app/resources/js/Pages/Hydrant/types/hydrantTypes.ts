import { InjectionKey } from "vue"

interface HydrantTypes {
    id?: number,
    name?: string,
    longitude: number,
    latitude: number,
    updated_at?: string,
    created_at?: string
}

interface PaginationTypes {
    current_page: number,
    from: number,
    per_page:number,
    to: number,
    total: number,
}

export interface HydrantPaginationTypes extends PaginationTypes {
    data:HydrantTypes[]
}

interface FilterTypes {
    id?: number,
    page: number,
    sortBy: string | 'id',
    sortType: 'asc'|'desc',
    rows: number,
    searchString?: string,
}


export interface ViewTypes {
    latitude: number, 
    longitude: number, 
    zoom: number
}

export type SetViewKeyType = InjectionKey<(values: ViewTypes) => void>

export {
    type HydrantTypes,
    type PaginationTypes,
    type FilterTypes
}

