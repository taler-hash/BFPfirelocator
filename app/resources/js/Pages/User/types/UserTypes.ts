import { InjectionKey } from "vue"

interface StationTypes {
    id?: number,
    name: string,
    location: string,
    longitude: number|null,
    latitude: number|null,
    updated_at?: string,
    created_at?: string
}

export interface UserTypes {
    id?: number|null,
    user_name?: string,
    name?: string,
    position?: string|null,
    password?:string|null,
    updated_at?: string,
    created_at?: string,
    station?: StationTypes,
    status?: string| null
}

export interface UserFormTypes extends UserTypes {
    role?: string
    station_id?: number|null
}

interface PaginationTypes {
    current_page: number,
    from: number,
    per_page:number,
    to: number,
    total: number,
}

export interface UserPaginationTypes extends PaginationTypes {
    data:  UserTypes[]
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
    type StationTypes,
    type PaginationTypes,
    type FilterTypes
}

