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

interface StationWithUserTypes {
    users: UserTypes[]
}

interface UserTypes {
    id?: number,
    name: string,
    position?: string,
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

export interface StationPaginationTypes extends PaginationTypes {
    data:StationTypes[]
}

export interface StationWithUserPaginationTypes extends PaginationTypes {
    data:  StationWithUserTypes[]
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

